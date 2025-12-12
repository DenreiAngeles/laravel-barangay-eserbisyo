<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MrShan0\PHPFirestore\FirestoreClient;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class AuthController extends Controller
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

    // --- LOGIN ---
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            // Direct HTTP call to Firebase Auth API for Login
            $response = Http::post("https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key={$this->apiKey}", [
                'email' => $request->email,
                'password' => $request->password,
                'returnSecureToken' => true
            ]);

            if ($response->failed()) {
                throw new \Exception('Invalid credentials');
            }

            $user = $response->json();

            Session::put('firebase_user', [
                'uid' => $user['localId'],
                'email' => $user['email'],
                'idToken' => $user['idToken']
            ]);

            return redirect()->route('resident.home');

        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Invalid credentials or user not found.']);
        }
    }

        // --- FORGOT PASSWORD ---
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            // Send Password Reset Email via Firebase REST API
            $response = Http::post("https://identitytoolkit.googleapis.com/v1/accounts:sendOobCode?key={$this->apiKey}", [
                'requestType' => 'PASSWORD_RESET',
                'email' => $request->email,
            ]);

            if ($response->failed()) {
                throw new \Exception('Unable to send reset link. Please verify the email address.');
            }

            return back()->with('success', 'Password reset link sent! Please check your email.');

        } catch (\Exception $e) {
            return back()->withErrors(['email' => $e->getMessage()]);
        }
    }

    // --- REGISTER ---
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'validId' => 'required|image|max:5120',
            'selfie' => 'required|image|max:5120',
        ]);

        try {
            // 1. Create User in Firebase Auth
            $response = Http::post("https://identitytoolkit.googleapis.com/v1/accounts:signUp?key={$this->apiKey}", [
                'email' => $request->email,
                'password' => $request->password,
                'returnSecureToken' => true
            ]);

            if ($response->failed()) {
                $errorMsg = $response->json()['error']['message'] ?? 'Unknown Error';
                if (str_contains($errorMsg, 'EMAIL_EXISTS')) {
                    throw new \Exception('This email is already registered.');
                }
                throw new \Exception('Auth Error: ' . $errorMsg);
            }

            $newUser = $response->json();
            $uid = $newUser['localId'];
            $idToken = $newUser['idToken'];

            // 2. Upload Files
            $idPhotoUrl = $this->uploadFile($request->file('validId'), "user_documents/{$uid}/id_photo.jpg", $idToken);
            $selfiePhotoUrl = $this->uploadFile($request->file('selfie'), "user_documents/{$uid}/selfie_photo.jpg", $idToken);
            // 3. Prepare Dates
            $now = Carbon::now('Asia/Manila');
            $birthDate = Carbon::parse($request->birthdate, 'Asia/Manila')->startOfDay();

            // 4. Prepare Data
            $userData = [
                'uid' => $uid,
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'phoneNumber' => $request->phoneNumber,
                'address' => $request->address,
                'civilStatus' => $request->civilStatus,

                // Pass Carbon objects
                'birthdate' => $birthDate,
                'registrationDate' => $now,

                'idPhotoUrl' => $idPhotoUrl,
                'selfiePhotoUrl' => $selfiePhotoUrl,
                'profilePictureUrl' => null,

                'verificationStatus' => 'pending',
                'rejectionReason' => null,
                'approvedBy' => null,
                'approvedAt' => null,

                'pushNotifications' => true,
                'emailNotifications' => true,
                'emergencyAlerts' => true,
                'fcmTokens' => null,
            ];

            // 5. Save to Firestore
            $firestoreUrl = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/users/{$uid}";

            $dbResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $idToken
            ])->patch($firestoreUrl, $this->formatForFirestore($userData));

            if ($dbResponse->failed()) {
                throw new \Exception("Database Error: " . $dbResponse->body());
            }

            return redirect()->route('login')->with('success', 'Registration successful! Please login.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()])->withInput();
        }
    }

    // --- LOGOUT ---
    public function logout()
    {
        Session::forget('firebase_user');
        return redirect()->route('login');
    }

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
            } elseif ($value instanceof \DateTimeInterface) {
                $date = Carbon::instance($value);
                $fields[$key] = ['timestampValue' => $date->setTimezone('UTC')->format('Y-m-d\TH:i:s\Z')];
            } else {
                $fields[$key] = ['stringValue' => (string)$value];
            }
        }
        return ['fields' => $fields];
    }
}
