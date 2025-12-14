<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MrShan0\PHPFirestore\FirestoreClient;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ResidentController extends Controller
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

    // HOME DASHBOARD
    public function home()
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        $sessionUser = Session::get('firebase_user');
        $uid = $sessionUser['uid'];
        $idToken = $sessionUser['idToken'];

        $tickets = [];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $idToken
            ])->get(
                "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/tickets"
            );

            if ($response->successful() && isset($response->json()['documents'])) {
                foreach ($response->json()['documents'] as $doc) {
                    $data = $this->parseFirestoreDocument($doc);

                    // Only tickets created by this user
                    if (($data['userId'] ?? null) === $uid) {
                        $tickets[] = [
                            'id' => basename($doc['name']),
                            'data' => $data
                        ];
                    }
                }
            }

            // Newest first
            usort($tickets, function ($a, $b) {
                return strtotime($b['data']['createdAt'] ?? '')
                     <=> strtotime($a['data']['createdAt'] ?? '');
            });

        } catch (\Exception $e) {
            // Fail silently – home page should still load
            $tickets = [];
        }

        return view('resident.home', compact('tickets'));
    }

    // OLD METHOD – SAFE REDIRECT
    public function index()
    {
        return redirect()->route('resident.home');
    }

    // Profile Page
    public function profile()
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        $sessionUser = Session::get('firebase_user');
        $uid = $sessionUser['uid'];

        try {
            $doc = $this->firestore->getDocument('users/' . $uid);
            $userData = [
                'id' => basename($doc->getName()),
                'data' => $doc->toArray()
            ];
        } catch (\Exception $e) {
            $userData = null;
        }

        return view('resident.profile', ['user' => $userData]);
    }

    public function documents()
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        return view('resident.documents');
    }

    public function transparency()
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        return view('resident.transparency');
    }

    public function map()
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        return view('resident.map');
    }

    //HELPER: Firestore REST parser
    private function parseFirestoreDocument($doc)
    {
        $data = [];

        foreach ($doc['fields'] ?? [] as $key => $value) {
            if (isset($value['stringValue'])) {
                $data[$key] = $value['stringValue'];
            } elseif (isset($value['timestampValue'])) {
                $data[$key] = $value['timestampValue'];
            } elseif (isset($value['integerValue'])) {
                $data[$key] = (int) $value['integerValue'];
            }
        }

        return $data;
    }
}
