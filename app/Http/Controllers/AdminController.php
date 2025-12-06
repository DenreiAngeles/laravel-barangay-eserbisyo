<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MrShan0\PHPFirestore\FirestoreClient;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Setup Connection
        $projectId = env('FIREBASE_PROJECT_ID'); // <--- Check your .env or paste it here
        $apiKey = env('FIREBASE_API_KEY');       // <--- Paste your Web API Key here
        
        $firestore = new FirestoreClient($projectId, $apiKey);

        // 2. Fetch Users
        $users = [];
        try {
            $response = $firestore->listDocuments('users');
            
            if (isset($response['documents'])) {
                foreach ($response['documents'] as $doc) {
                    $users[] = [
                        'id' => basename($doc->getName()),
                        'data' => $doc->toArray()
                    ];
                }
            }
        } catch (\Exception $e) {
            // If it fails, we just show an empty list for now
            // In production, you might want to log this error
        }

        // 3. Return the View
        return view('admin.residents', ['users' => $users]);
    }
}