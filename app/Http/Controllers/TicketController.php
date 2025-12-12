<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MrShan0\PHPFirestore\FirestoreClient;
use Illuminate\Support\Facades\Session;

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
    public function index()
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        // TODO: Fetch user's tickets from Firestore
        // $sessionUser = Session::get('firebase_user');
        // $uid = $sessionUser['uid'];
        // $tickets = $this->firestore->collection('tickets')
        //     ->where('userId', '==', $uid)
        //     ->documents();

        return view('resident.tickets');
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

        // TODO: Validate and store ticket in Firestore

        return redirect()->route('resident.tickets')->with('success', 'Ticket created successfully!');
    }

    /**
     * Show a specific ticket
     */
    public function show($id)
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        // TODO: Fetch single ticket from Firestore
        // $ticket = $this->firestore->getDocument('tickets/' . $id);

        return view('resident.tickets.show', ['id' => $id]);
    }

    /**
     * Update ticket status or add comments
     */
    public function update(Request $request, $id)
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        // TODO: Update ticket in Firestore

        return redirect()->route('resident.tickets')->with('success', 'Ticket updated successfully!');
    }
}
