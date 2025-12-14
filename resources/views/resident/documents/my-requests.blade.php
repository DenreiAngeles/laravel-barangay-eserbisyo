@extends('layouts.app')

@section('title', 'My Requests')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Top Bar -->
    <div class="bg-white border-b border-gray-200 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center gap-4">
            <a href="{{ route('resident.home') }}" class="text-blue-600 hover:text-blue-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-semibold text-gray-900">My Requests</h1>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-6">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($requests->isEmpty())
            <div class="flex flex-col items-center justify-center py-20">
                <div class="bg-gray-100 rounded-full p-6 mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-gray-900 text-xl font-semibold mb-2">No Requests Yet</h3>
                <p class="text-gray-600 text-center mb-6">
                    You haven't submitted any document or benefit requests.
                </p>
                <a href="{{ route('resident.documents') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl transition-colors">
                    Browse Services
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($requests as $request)
                    <a href="{{ route('resident.requests.show', $request['id']) }}" 
                       class="block bg-white rounded-2xl p-5 shadow-sm hover:shadow-md transition-all border border-gray-200">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="text-gray-900 font-semibold">{{ $request['serviceName'] }}</h3>
                                <p class="text-sm text-gray-500">{{ $request['requestID'] }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                @if($request['status'] === 'approved') bg-green-100 text-green-800
                                @elseif($request['status'] === 'rejected') bg-red-100 text-red-800
                                @elseif($request['status'] === 'processing') bg-blue-100 text-blue-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $request['status'])) }}
                            </span>
                        </div>
                        
                        <div class="space-y-1 text-sm">
                            <p class="text-gray-600">
                                <span class="font-medium">Purpose:</span> {{ $request['purpose'] }}
                            </p>
                            <p class="text-gray-500">
                                Submitted: {{ \Carbon\Carbon::parse($request['dateSubmitted'])->format('M d, Y h:i A') }}
                            </p>
                        </div>

                        <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ $request['serviceCategory'] }}</span>
                            <span class="text-blue-600 text-sm font-medium">View Details →</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection