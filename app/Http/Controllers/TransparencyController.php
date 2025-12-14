<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class TransparencyController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index()
    {
        if (!Session::has('firebase_user')) {
            return redirect()->route('login');
        }

        try {
            // Fetch Budget Data
            $budgetData = $this->firebase->getDocument('budgetAndProjects', 'budget');

            // Fetch Projects (subcollection under budget)
            $projects = $this->getProjects();

            // Fetch Public Reports
            $reports = $this->firebase->getCollection('publicReports');
            if ($reports) {
                $reports = $reports->sortByDesc(function($report) {
                    return $report['uploadedAt'] ?? '';
                })->take(3)->values()->toArray();
            } else {
                $reports = [];
            }

            // Fetch Announcements
            $announcements = $this->firebase->getCollection('announcements');
            if ($announcements) {
                $announcements = $announcements->filter(function($announcement) {
                    return ($announcement['status'] ?? '') === 'published'
                        && ($announcement['isActive'] ?? true) === true;
                })->sortByDesc(function($announcement) {
                    return $announcement['createdAt'] ?? '';
                })->values()->toArray();
            } else {
                $announcements = [];
            }

            // Debug logging
            Log::info('Transparency Data:', [
                'budget' => $budgetData ? 'Found' : 'Not found',
                'projects_count' => count($projects),
                'reports_count' => count($reports),
                'announcements_count' => count($announcements)
            ]);

            return view('resident.transparency', [
                'budget' => $budgetData,
                'projects' => $projects,
                'reports' => $reports,
                'announcements' => $announcements
            ]);

        } catch (\Exception $e) {
            Log::error('Transparency Error: ' . $e->getMessage());

            return view('resident.transparency', [
                'budget' => null,
                'projects' => [],
                'reports' => [],
                'announcements' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get projects from subcollection
     * Since FirebaseService doesn't have a subcollection method,
     * we'll use the Firestore client directly
     */
    private function getProjects()
    {
        try {
            $projectId = env('FIREBASE_PROJECT_ID');
            $apiKey = env('FIREBASE_API_KEY');
            $sessionUser = Session::get('firebase_user');
            $idToken = $sessionUser['idToken'];

            // Fetch projects subcollection
            $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/budgetAndProjects/budget/projects";

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $idToken
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();
                $projects = [];

                if (isset($data['documents'])) {
                    foreach ($data['documents'] as $doc) {
                        $fields = $doc['fields'] ?? [];

                        $projects[] = [
                            'id' => basename($doc['name']),
                            'name' => $this->getFieldValue($fields, 'name'),
                            'description' => $this->getFieldValue($fields, 'description'),
                            'totalBudget' => $this->getFieldValue($fields, 'totalBudget'),
                            'progress' => $this->getFieldValue($fields, 'progress'),
                            'status' => $this->getFieldValue($fields, 'status'),
                            'startDate' => $this->getFieldValue($fields, 'startDate'),
                            'estimatedEndDate' => $this->getFieldValue($fields, 'estimatedEndDate'),
                            'createdAt' => $this->getFieldValue($fields, 'createdAt'),
                        ];
                    }

                    // Sort by createdAt descending
                    usort($projects, function($a, $b) {
                        return strcmp($b['createdAt'] ?? '', $a['createdAt'] ?? '');
                    });
                }

                return $projects;
            }

            return [];
        } catch (\Exception $e) {
            Log::error('Error fetching projects: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Helper function to extract field values from Firestore format
     */
    private function getFieldValue($fields, $fieldName)
    {
        if (!isset($fields[$fieldName])) {
            return null;
        }

        $field = $fields[$fieldName];

        // Handle different Firestore field types
        if (isset($field['stringValue'])) {
            return $field['stringValue'];
        } elseif (isset($field['integerValue'])) {
            return (int) $field['integerValue'];
        } elseif (isset($field['doubleValue'])) {
            return (float) $field['doubleValue'];
        } elseif (isset($field['booleanValue'])) {
            return (bool) $field['booleanValue'];
        } elseif (isset($field['timestampValue'])) {
            return $field['timestampValue'];
        } elseif (isset($field['nullValue'])) {
            return null;
        } elseif (isset($field['arrayValue'])) {
            return $field['arrayValue'];
        }

        return null;
    }
}
