<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MrShan0\PHPFirestore\FirestoreClient;
use Illuminate\Support\Facades\Session;

class ResidentController extends Controller
{
    public function index()
    {
        // 1. Security Check
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        // 2. Get Current User ID from Session
        $sessionUser = Session::get('firebase_user');
        $uid = $sessionUser['uid'];

        // 3. Connect to Firestore
        $projectId = env('FIREBASE_PROJECT_ID');
        $apiKey = env('FIREBASE_API_KEY');
        $firestore = new FirestoreClient($projectId, $apiKey);

        // 4. Fetch ONLY this user's document
        try {
            $doc = $firestore->getDocument('users/' . $uid);
            
            // FIX: Use the correct methods found via "Detective Mode" earlier
            $userData = [
                'id' => basename($doc->getName()), // Get ID from path
                'data' => $doc->toArray()          // Get clean fields
            ];

        } catch (\Exception $e) {
            // Fallback if user exists in Auth but not in Firestore yet
            $userData = null;
        }

        return view('resident.dashboard', ['user' => $userData]);
    }
}