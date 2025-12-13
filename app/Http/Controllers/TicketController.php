<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MrShan0\PHPFirestore\FirestoreClient;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class TicketController extends Controller
{
    protected $firestore;
    protected $projectId;
    protected $apiKey;

    public function __construct()
    {
        $this->projectId = env('FIREBASE_PROJECT_ID');
        $this->apiKey = env('FIREBASE_API_KEY');
        $this->firestore = new FirestoreClient($this->projectId, $this->apiKey);
    }

    /**
     * Display all user's tickets
     */
    public function index(Request $request)
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        $sessionUser = Session::get('firebase_user');
        $uid = $sessionUser['uid'];
        $idToken = $sessionUser['idToken'];

        try {
            // Fetch tickets from Firestore
            $firestoreUrl = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/tickets";
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $idToken
            ])->get($firestoreUrl);

            $tickets = [];
            if ($response->successful() && isset($response->json()['documents'])) {
                foreach ($response->json()['documents'] as $doc) {
                    $data = $this->parseFirestoreDocument($doc);
                    if ($data['userId'] === $uid) {
                        $tickets[] = [
                            'id' => basename($doc['name']),
                            'data' => $data
                        ];
                    }
                }
            }

            // Sort by creation date (newest first)
            usort($tickets, function($a, $b) {
                $timeA = strtotime($a['data']['createdAt'] ?? '1970-01-01');
                $timeB = strtotime($b['data']['createdAt'] ?? '1970-01-01');
                return $timeB - $timeA;
            });

            // Filter by status if requested
            $filterStatus = $request->query('status', 'all');
            if ($filterStatus !== 'all') {
                $tickets = array_filter($tickets, function($ticket) use ($filterStatus) {
                    return strtolower($ticket['data']['status'] ?? '') === strtolower($filterStatus);
                });
            }

            return view('resident.tickets.index', compact('tickets', 'filterStatus'));

        } catch (\Exception $e) {
            return view('resident.tickets.index', [
                'tickets' => [],
                'filterStatus' => 'all',
                'error' => 'Failed to load tickets: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show form to create a new ticket
     */
    public function create()
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        return view('resident.tickets.create');
    }

    /**
     * Store a new ticket
     */
    public function store(Request $request)
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'location' => 'required|string|max:500',
            'description' => 'required|string',
            'attachments.*' => 'nullable|file|max:5120', // 5MB max
        ]);

        $sessionUser = Session::get('firebase_user');
        $uid = $sessionUser['uid'];
        $idToken = $sessionUser['idToken'];

        try {
            // Generate ticket ID
            $ticketId = 'TKT-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            // Upload attachments if any
            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $url = $this->uploadFile($file, "tickets/{$ticketId}/" . $file->getClientOriginalName(), $idToken);
                    $attachments[] = [
                        'type' => str_starts_with($file->getMimeType(), 'image/') ? 'photo' : 'file',
                        'url' => $url,
                        'name' => $file->getClientOriginalName(),
                        'uploadedAt' => Carbon::now('Asia/Manila')
                    ];
                }
            }

            // Prepare ticket data
            $now = Carbon::now('Asia/Manila');
            $ticketData = [
                'ticketId' => $ticketId,
                'userId' => $uid,
                'title' => $request->title,
                'category' => $request->category,
                'location' => $request->location,
                'description' => $request->description,
                'status' => 'pending',
                'priority' => $this->determinePriority($request->category),
                'attachments' => $attachments,
                'collaborators' => $request->collaborators ? explode(',', $request->collaborators) : [],
                'comments' => [],
                'commentCount' => 0,
                'createdAt' => $now,
                'updatedAt' => $now,
                'resolvedAt' => null,
                'resolvedBy' => null
            ];

            // Save to Firestore
            $firestoreUrl = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/tickets/{$ticketId}";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $idToken
            ])->patch($firestoreUrl, $this->formatForFirestore($ticketData));

            if ($response->failed()) {
                throw new \Exception("Failed to create ticket: " . $response->body());
            }

            return redirect()->route('resident.tickets.show', $ticketId)->with('success', 'Ticket created successfully!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create ticket: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show a specific ticket
     */
    public function show($id)
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        $sessionUser = Session::get('firebase_user');
        $uid = $sessionUser['uid'];
        $idToken = $sessionUser['idToken'];

        try {
            $firestoreUrl = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/tickets/{$id}";
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $idToken
            ])->get($firestoreUrl);

            if ($response->failed()) {
                throw new \Exception("Ticket not found");
            }

            $doc = $response->json();
            $ticketData = $this->parseFirestoreDocument($doc);

            // Check if user owns this ticket
            if ($ticketData['userId'] !== $uid) {
                return redirect()->route('resident.tickets')->withErrors(['error' => 'You do not have permission to view this ticket.']);
            }

            $ticket = [
                'id' => $id,
                'data' => $ticketData
            ];

            return view('resident.tickets.show', compact('ticket'));

        } catch (\Exception $e) {
            return redirect()->route('resident.tickets')->withErrors(['error' => 'Ticket not found.']);
        }
    }

    /**
     * Add comment to ticket
     */
    public function addComment(Request $request, $id)
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        $request->validate([
            'comment' => 'required|string'
        ]);

        $sessionUser = Session::get('firebase_user');
        $uid = $sessionUser['uid'];
        $idToken = $sessionUser['idToken'];

        try {
            // Fetch current ticket
            $firestoreUrl = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/tickets/{$id}";
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $idToken
            ])->get($firestoreUrl);

            if ($response->failed()) {
                throw new \Exception("Ticket not found");
            }

            $doc = $response->json();
            $ticketData = $this->parseFirestoreDocument($doc);

            // Add new comment
            $comments = $ticketData['comments'] ?? [];
            $comments[] = [
                'userId' => $uid,
                'comment' => $request->comment,
                'createdAt' => Carbon::now('Asia/Manila')
            ];

            // Update ticket
            $updateData = [
                'comments' => $comments,
                'commentCount' => count($comments),
                'updatedAt' => Carbon::now('Asia/Manila')
            ];

            $updateResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $idToken
            ])->patch($firestoreUrl, $this->formatForFirestore($updateData));

            if ($updateResponse->failed()) {
                throw new \Exception("Failed to add comment");
            }

            return back()->with('success', 'Comment added successfully!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to add comment: ' . $e->getMessage()]);
        }
    }

    // Helper Methods

    private function uploadFile($file, $path, $idToken)
    {
        $bucketName = env('FIREBASE_STORAGE_BUCKET');
        if (!$bucketName) throw new \Exception("FIREBASE_STORAGE_BUCKET is missing in .env");

        $encodedPath = urlencode($path);
        $url = "https://firebasestorage.googleapis.com/v0/b/{$bucketName}/o?name={$encodedPath}";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $idToken,
            'Content-Type' => $file->getMimeType(),
        ])->send('POST', $url, ['body' => file_get_contents($file->getRealPath())]);

        if (!$response->successful()) throw new \Exception("File upload failed: " . $response->body());

        $token = $response->json()['downloadTokens'] ?? '';
        return "https://firebasestorage.googleapis.com/v0/b/{$bucketName}/o/{$encodedPath}?alt=media&token={$token}";
    }

    private function determinePriority($category)
    {
        $highPriorityCategories = ['Emergency', 'Health', 'Safety'];
        return in_array($category, $highPriorityCategories) ? 'high' : 'medium';
    }

    private function formatForFirestore($data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                $fields[$key] = ['nullValue' => null];
            } elseif (is_bool($value)) {
                $fields[$key] = ['booleanValue' => $value];
            } elseif (is_int($value)) {
                $fields[$key] = ['integerValue' => $value];
            } elseif (is_float($value)) {
                $fields[$key] = ['doubleValue' => $value];
            } elseif (is_array($value)) {
                $fields[$key] = ['arrayValue' => ['values' => $this->formatArrayForFirestore($value)]];
            } elseif ($value instanceof \DateTimeInterface) {
                $date = Carbon::instance($value);
                $fields[$key] = ['timestampValue' => $date->setTimezone('UTC')->format('Y-m-d\TH:i:s\Z')];
            } else {
                $fields[$key] = ['stringValue' => (string)$value];
            }
        }
        return ['fields' => $fields];
    }

    private function formatArrayForFirestore($array)
    {
        $values = [];
        foreach ($array as $item) {
            if (is_array($item)) {
                $values[] = ['mapValue' => $this->formatForFirestore($item)];
            } elseif (is_string($item)) {
                $values[] = ['stringValue' => $item];
            } elseif (is_int($item)) {
                $values[] = ['integerValue' => $item];
            } elseif (is_bool($item)) {
                $values[] = ['booleanValue' => $item];
            } elseif ($item instanceof \DateTimeInterface) {
                $date = Carbon::instance($item);
                $values[] = ['timestampValue' => $date->setTimezone('UTC')->format('Y-m-d\TH:i:s\Z')];
            }
        }
        return $values;
    }

    private function parseFirestoreDocument($doc)
    {
        $data = [];
        if (isset($doc['fields'])) {
            foreach ($doc['fields'] as $key => $value) {
                $data[$key] = $this->parseFirestoreValue($value);
            }
        }
        return $data;
    }

    private function parseFirestoreValue($value)
    {
        if (isset($value['stringValue'])) return $value['stringValue'];
        if (isset($value['integerValue'])) return (int)$value['integerValue'];
        if (isset($value['doubleValue'])) return (float)$value['doubleValue'];
        if (isset($value['booleanValue'])) return $value['booleanValue'];
        if (isset($value['timestampValue'])) return $value['timestampValue'];
        if (isset($value['nullValue'])) return null;
        if (isset($value['arrayValue']['values'])) {
            return array_map(fn($v) => $this->parseFirestoreValue($v), $value['arrayValue']['values']);
        }
        if (isset($value['mapValue']['fields'])) {
            $map = [];
            foreach ($value['mapValue']['fields'] as $k => $v) {
                $map[$k] = $this->parseFirestoreValue($v);
            }
            return $map;
        }
        return null;
    }
}