<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use MrShan0\PHPFirestore\FirestoreClient;
use Illuminate\Support\Facades\Log;

class ServiceRequestController extends Controller
{
    protected $firebase;
    protected $firestore;
    protected $projectId;
    protected $apiKey;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
        $this->projectId = env('FIREBASE_PROJECT_ID');
        $this->apiKey = env('FIREBASE_API_KEY');
        $this->firestore = new FirestoreClient($this->projectId, $this->apiKey);
    }

    public function index()
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        $sessionUser = Session::get('firebase_user');
        $uid = $sessionUser['uid'];

        // 🔥 FIX: Changed from 'residents' to 'users'
        $resident = $this->firebase->getDocument('users', $uid);

        // ❗ HARD FAIL SAFE
        if (!$resident) {
            Session::forget('firebase_user');
            return redirect()->route('login')->withErrors([
                'error' => 'User record not found. Please log in again.'
            ]);
        }

        // ✅ SINGLE SOURCE OF TRUTH
        $isVerified = isset($resident['verificationStatus'])
            && strtolower($resident['verificationStatus']) === 'verified';

        // 🔄 UPDATE SESSION SO IT'S ALWAYS FRESH
        Session::put('firebase_user', array_merge($sessionUser, [
            'verificationStatus' => $resident['verificationStatus'] ?? null,
        ]));

        $allServices = $this->firebase->getCollection('services');

        $documents = $allServices->filter(function ($service) {
            return strtolower($service['category'] ?? '') === 'documents'
                && ($service['isActive'] ?? true);
        })->values();

        $benefits = $allServices->filter(function ($service) {
            return strtolower($service['category'] ?? '') === 'benefits'
                && ($service['isActive'] ?? true);
        })->values();

        return view('resident.documents.index', compact(
            'documents',
            'benefits',
            'isVerified'
        ));
    }

    public function show($serviceId)
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        $service = $this->firebase->getDocument('services', $serviceId);
        
        if (!$service) {
            abort(404);
        }

        $sessionUser = Session::get('firebase_user');
        return view('resident.documents.show', compact('service', 'sessionUser'));
    }

    public function store(Request $request, $serviceId)
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'purpose' => 'required|string',
            'requirements' => 'required|array',
            'requirements.*' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        try {
            $sessionUser = Session::get('firebase_user');
            $uid = $sessionUser['uid'];

            $service = $this->firebase->getDocument('services', $serviceId);
            
            if (!$service) {
                return back()->withErrors(['error' => 'Service not found']);
            }

            $requestId = $this->generateFirebaseRequestId();

            $submittedRequirements = [];
            $requirements = $service['requirements'] ?? [];

            foreach ($requirements as $index => $req) {
                if ($request->hasFile("requirements.$index")) {
                    $file = $request->file("requirements.$index");
                    $timestamp = now()->timestamp;
                    $cleanName = preg_replace('/[^a-zA-Z0-9]/', '_', $req['name']);
                    $fileName = $uid . "_{$timestamp}_{$cleanName}." . $file->getClientOriginalExtension();
                    
                    $path = $file->storeAs(
                        "service_requests/{$requestId}",
                        $fileName,
                        'public'
                    );

                    $submittedRequirements[] = [
                        'requirementName' => $req['name'],
                        'fileName' => $file->getClientOriginalName(),
                        'fileType' => $file->getMimeType(),
                        'fileUrl' => Storage::url($path),
                        'note' => '',
                        'uploadedAt' => now()->toIso8601String(),
                        'approvalStatus' => 'pending',
                    ];
                }
            }

            $requestData = [
                'requestID' => $requestId,
                'userId' => $uid,
                'serviceId' => $serviceId,
                'serviceName' => $service['name'],
                'serviceCategory' => $service['category'],
                'residentName' => $validated['name'],
                'contactNumber' => $validated['contact_number'],
                'email' => $sessionUser['email'],
                'purpose' => $validated['purpose'],
                'status' => 'to_be_reviewed',
                'dateSubmitted' => now()->toIso8601String(),
                'submittedRequirements' => $submittedRequirements,
            ];

            $this->firebase->createDocument('serviceRequests', $requestData);

            return redirect()->route('resident.my-requests')
                ->with('success', 'Request submitted successfully!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error submitting request: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function myRequests()
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        $sessionUser = Session::get('firebase_user');
        $uid = $sessionUser['uid'];
        
        $requests = $this->firebase->query('serviceRequests', [
            ['field' => 'userId', 'op' => 'EQUAL', 'value' => $uid]
        ]);

        $requests = $requests->sortByDesc(function ($req) {
            return $req['dateSubmitted'] ?? '';
        })->values();

        return view('resident.documents.my-requests', compact('requests'));
    }

    public function showStatus($requestId)
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        $request = $this->firebase->getDocument('serviceRequests', $requestId);
        
        if (!$request) {
            abort(404);
        }

        $sessionUser = Session::get('firebase_user');
        $uid = $sessionUser['uid'];

        if ($request['userId'] !== $uid) {
            abort(403);
        }

        return view('resident.documents.status', compact('request'));
    }

    public function resubmitRequirement(Request $request, $requestId, $index)
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        $serviceRequest = $this->firebase->getDocument('serviceRequests', $requestId);
        
        $sessionUser = Session::get('firebase_user');
        $uid = $sessionUser['uid'];

        if (!$serviceRequest || $serviceRequest['userId'] !== $uid) {
            abort(403);
        }

        $validated = $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        try {
            $file = $request->file('file');
            $requirements = $serviceRequest['submittedRequirements'] ?? [];
            
            $timestamp = now()->timestamp;
            $cleanName = preg_replace('/[^a-zA-Z0-9]/', '_', $requirements[$index]['requirementName']);
            $fileName = $uid . "_{$timestamp}_RESUBMIT_{$cleanName}." . $file->getClientOriginalExtension();
            
            $oldPath = str_replace('/storage/', '', $requirements[$index]['fileUrl']);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            $path = $file->storeAs(
                "service_requests/{$requestId}",
                $fileName,
                'public'
            );

            $requirements[$index] = [
                'requirementName' => $requirements[$index]['requirementName'],
                'fileName' => $file->getClientOriginalName(),
                'fileType' => $file->getMimeType(),
                'fileUrl' => Storage::url($path),
                'note' => 'Resubmitted by user',
                'uploadedAt' => now()->toIso8601String(),
                'approvalStatus' => 'pending',
            ];

            $updateData = [
                'submittedRequirements' => $requirements,
                'status' => 'to_be_reviewed',
                'processedAt' => null,
                'rejectionReason' => null,
            ];

            $this->firebase->updateDocument('serviceRequests', $requestId, $updateData);

            return back()->with('success', 'Requirement resubmitted successfully!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error uploading file: ' . $e->getMessage()]);
        }
    }

    protected function generateFirebaseRequestId()
    {
        $counter = $this->firebase->getDocument('counters', 'serviceRequests');
        
        if (!$counter) {
            $this->firebase->createDocument('counters', ['lastNumber' => 1], 'serviceRequests');
            return 'SR-001';
        }

        $nextNumber = ($counter['lastNumber'] ?? 0) + 1;
        
        $this->firebase->updateDocument('counters', 'serviceRequests', ['lastNumber' => $nextNumber]);
        
        return 'SR-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}