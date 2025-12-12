<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MrShan0\PHPFirestore\FirestoreClient;
use Illuminate\Support\Facades\Session;

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

    // Home Dashboard (NEW - This is what should show first)
    public function home()
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        // For now, we'll pass empty data or static data
        // Later you can fetch real announcements and tickets from Firebase
        return view('resident.home');
    }

    // OLD METHOD - REMOVE THIS OR RENAME IT
    // This is causing the issue because it's trying to load 'resident.dashboard'
    public function index()
    {
        // Redirect to the new home route instead
        return redirect()->route('resident.home');
    }

    // Profile Page (This will show the user's personal info)
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

    // Tickets Page
    public function tickets()
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        return view('resident.tickets');
    }

    // Document Requests Page
    public function documents()
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        return view('resident.documents');
    }

    // Transparency Page
    public function transparency()
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        return view('resident.transparency');
    }

    // Barangay Map Page
    public function map()
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        return view('resident.map');
    }
}
