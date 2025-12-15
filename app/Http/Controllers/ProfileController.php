<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index()
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        $sessionUser = Session::get('firebase_user');
        $uid = $sessionUser['uid'];

        // Fetch user data from Firebase
        $userData = $this->firebase->getDocument('users', $uid);

        if (!$userData) {
            Session::forget('firebase_user');
            return redirect()->route('login')->withErrors([
                'error' => 'User record not found. Please log in again.'
            ]);
        }

        // Prepare user data for the view
        $user = [
            'data' => $userData
        ];

        // Update session with latest verification status
        Session::put('firebase_user', array_merge($sessionUser, [
            'verificationStatus' => $userData['verificationStatus'] ?? null,
        ]));

        return view('resident.profile', compact('user'));
    }
}