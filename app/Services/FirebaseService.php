<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class FirebaseService
{
    protected $apiKey;
    protected $projectId;
    protected $databaseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.firebase.api_key') ?? env('FIREBASE_API_KEY');
        $this->projectId = config('services.firebase.project_id') ?? env('FIREBASE_PROJECT_ID');
        $this->databaseUrl = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents";
    }

    /**
     * Get authenticated ID token from session
     */
    protected function getIdToken()
    {
        $user = Session::get('firebase_user');
        return $user['idToken'] ?? null;
    }

    /**
     * Get HTTP client with authentication
     */
    protected function http()
    {
        $idToken = $this->getIdToken();
        
        if ($idToken) {
            return Http::withHeaders([
                'Authorization' => 'Bearer ' . $idToken
            ]);
        }
        
        return Http::asJson();
    }

    /**
     * Get all documents from a collection
     */
    public function getCollection($collection, $filters = [])
    {
        $url = "{$this->databaseUrl}/{$collection}";
        
        $response = $this->http()->get($url);
        
        if ($response->successful()) {
            $documents = $response->json()['documents'] ?? [];
            return collect($documents)->map(function ($doc) {
                return $this->parseDocument($doc);
            });
        }

        return collect([]);
    }

    /**
     * Get a single document
     */
    public function getDocument($collection, $documentId)
    {
        $url = "{$this->databaseUrl}/{$collection}/{$documentId}";
        
        $response = $this->http()->get($url);
        
        if ($response->successful()) {
            return $this->parseDocument($response->json());
        }

        return null;
    }

    /**
     * Create a document
     */
    public function createDocument($collection, $data, $documentId = null)
    {
        $url = $documentId 
            ? "{$this->databaseUrl}/{$collection}?documentId={$documentId}"
            : "{$this->databaseUrl}/{$collection}";
        
        $response = $this->http()->post($url, [
            'fields' => $this->formatFields($data)
        ]);

        if ($response->successful()) {
            return $this->parseDocument($response->json());
        }

        return null;
    }

    /**
     * Update a document
     */
    public function updateDocument($collection, $documentId, $data)
    {
        $url = "{$this->databaseUrl}/{$collection}/{$documentId}";
        
        $response = $this->http()->patch($url, [
            'fields' => $this->formatFields($data)
        ]);

        if ($response->successful()) {
            return $this->parseDocument($response->json());
        }

        return null;
    }

    /**
     * Delete a document
     */
    public function deleteDocument($collection, $documentId)
    {
        $url = "{$this->databaseUrl}/{$collection}/{$documentId}";
        
        $response = $this->http()->delete($url);

        return $response->successful();
    }

    /**
     * Query collection with filters
     */
    public function query($collection, $filters = [])
    {
        $url = "{$this->databaseUrl}:runQuery";
        
        $structuredQuery = [
            'from' => [['collectionId' => $collection]]
        ];

        // Add filters
        if (!empty($filters)) {
            $structuredQuery['where'] = $this->buildWhereClause($filters);
        }

        $response = $this->http()->post($url, [
            'structuredQuery' => $structuredQuery
        ]);

        if ($response->successful()) {
            $results = $response->json();
            return collect($results)->map(function ($result) {
                return isset($result['document']) ? $this->parseDocument($result['document']) : null;
            })->filter();
        }

        return collect([]);
    }

    /**
     * Parse Firestore document to associative array
     */
    protected function parseDocument($document)
    {
        if (!isset($document['fields'])) {
            return null;
        }

        $data = [];
        
        // Extract document ID from name
        if (isset($document['name'])) {
            $nameParts = explode('/', $document['name']);
            $data['id'] = end($nameParts);
        }

        foreach ($document['fields'] as $key => $value) {
            $data[$key] = $this->parseValue($value);
        }

        return $data;
    }

    /**
     * Parse Firestore value types
     */
    protected function parseValue($value)
    {
        if (isset($value['stringValue'])) return $value['stringValue'];
        if (isset($value['integerValue'])) return (int) $value['integerValue'];
        if (isset($value['doubleValue'])) return (float) $value['doubleValue'];
        if (isset($value['booleanValue'])) return $value['booleanValue'];
        if (isset($value['timestampValue'])) return $value['timestampValue'];
        if (isset($value['nullValue'])) return null;
        
        if (isset($value['arrayValue'])) {
            return collect($value['arrayValue']['values'] ?? [])->map(function ($item) {
                return $this->parseValue($item);
            })->toArray();
        }
        
        if (isset($value['mapValue'])) {
            $map = [];
            foreach ($value['mapValue']['fields'] ?? [] as $key => $val) {
                $map[$key] = $this->parseValue($val);
            }
            return $map;
        }

        return null;
    }

    /**
     * Format data for Firestore
     */
    protected function formatFields($data)
    {
        $fields = [];
        
        foreach ($data as $key => $value) {
            $fields[$key] = $this->formatValue($value);
        }

        return $fields;
    }

    /**
     * Format value for Firestore
     */
    protected function formatValue($value)
    {
        if (is_string($value)) return ['stringValue' => $value];
        if (is_int($value)) return ['integerValue' => $value];
        if (is_float($value)) return ['doubleValue' => $value];
        if (is_bool($value)) return ['booleanValue' => $value];
        if (is_null($value)) return ['nullValue' => null];
        
        if (is_array($value)) {
            // Check if associative array (map) or indexed array
            if (array_keys($value) === range(0, count($value) - 1)) {
                // Indexed array
                return [
                    'arrayValue' => [
                        'values' => array_map(fn($v) => $this->formatValue($v), $value)
                    ]
                ];
            } else {
                // Associative array (map)
                $fields = [];
                foreach ($value as $k => $v) {
                    $fields[$k] = $this->formatValue($v);
                }
                return ['mapValue' => ['fields' => $fields]];
            }
        }

        return ['stringValue' => (string) $value];
    }

    /**
     * Build WHERE clause for queries
     */
    protected function buildWhereClause($filters)
    {
        $compositeFilter = [
            'compositeFilter' => [
                'op' => 'AND',
                'filters' => []
            ]
        ];

        foreach ($filters as $filter) {
            $compositeFilter['compositeFilter']['filters'][] = [
                'fieldFilter' => [
                    'field' => ['fieldPath' => $filter['field']],
                    'op' => $filter['op'] ?? 'EQUAL',
                    'value' => $this->formatValue($filter['value'])
                ]
            ];
        }

        return $compositeFilter;
    }
}