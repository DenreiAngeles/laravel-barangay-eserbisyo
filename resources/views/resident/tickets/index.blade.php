@extends('layouts.app')

@section('title', 'Tickets')
@section('page-title', 'Ticketing System')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Emergency Banner -->
    <div class="bg-gradient-to-r from-red-500 to-orange-500 rounded-xl p-6 mb-6 text-white shadow-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="font-bold text-lg">Emergency Report</h3>
                <p class="text-sm mt-1">Need immediate assistance? Report an emergency</p>
            </div>
            <a href="{{ route('resident.tickets.create') }}?category=Emergency" class="bg-white text-red-600 px-6 py-2 rounded-lg font-bold hover:bg-red-50 transition">
                Report Now
            </a>
        </div>
    </div>

    <!-- Header with New Ticket Button -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">All Tickets</h2>
            <p class="text-gray-500 text-sm mt-1">Track and manage your service requests</p>
        </div>
        <a href="{{ route('resident.tickets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center gap-2 shadow-lg hover:shadow-xl transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Ticket
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="flex border-b border-gray-200 overflow-x-auto">
            <a href="?status=all" class="px-6 py-4 text-sm font-medium {{ $filterStatus === 'all' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700' }} whitespace-nowrap">
                All Tickets
            </a>
            <a href="?status=pending" class="px-6 py-4 text-sm font-medium {{ $filterStatus === 'pending' ? 'border-b-2 border-yellow-600 text-yellow-600' : 'text-gray-500 hover:text-gray-700' }} whitespace-nowrap">
                Pending Review
            </a>
            <a href="?status=in-progress" class="px-6 py-4 text-sm font-medium {{ $filterStatus === 'in-progress' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700' }} whitespace-nowrap">
                In Progress
            </a>
            <a href="?status=resolved" class="px-6 py-4 text-sm font-medium {{ $filterStatus === 'resolved' ? 'border-b-2 border-green-600 text-green-600' : 'text-gray-500 hover:text-gray-700' }} whitespace-nowrap">
                Resolved
            </a>
        </div>
    </div>

    <!-- Error Message -->
    @if(isset($error))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
            <p class="text-red-700 text-sm">{{ $error }}</p>
        </div>
    @endif

    <!-- Tickets Grid -->
    @if(count($tickets) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tickets as $ticket)
                <a href="{{ route('resident.tickets.show', $ticket['id']) }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 hover:shadow-md hover:border-blue-300 transition group">
                    
                    <!-- Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:text-blue-600 transition">
                                {{ $ticket['data']['title'] ?? 'Untitled' }}
                            </h3>
                            <div class="flex items-center space-x-2 text-xs text-gray-500">
                                <span class="font-medium">{{ $ticket['data']['ticketId'] ?? 'N/A' }}</span>
                                <span>•</span>
                                <span>{{ $ticket['data']['category'] ?? 'General' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Description Preview -->
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                        {{ $ticket['data']['description'] ?? 'No description provided.' }}
                    </p>

                    <!-- Footer -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div class="flex items-center text-xs text-gray-500 space-x-3">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($ticket['data']['createdAt'])->format('M d, Y') }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                {{ $ticket['data']['commentCount'] ?? 0 }}
                            </span>
                        </div>

                        <!-- Status Badge -->
                        @php
                            $status = strtolower($ticket['data']['status'] ?? 'pending');
                            $badgeClasses = match($status) {
                                'resolved' => 'bg-green-100 text-green-700',
                                'in-progress' => 'bg-blue-100 text-blue-700',
                                'rejected' => 'bg-red-100 text-red-700',
                                default => 'bg-yellow-100 text-yellow-700'
                            };
                            $statusLabel = ucwords(str_replace('-', ' ', $status));
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $badgeClasses }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                </a>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="max-w-md mx-auto">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">No tickets found</h3>
                <p class="text-gray-500 mb-6">
                    @if($filterStatus !== 'all')
                        No tickets with "{{ ucwords($filterStatus) }}" status.
                    @else
                        You haven't created any tickets yet.
                    @endif
                </p>
                <a href="{{ route('resident.tickets.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create Your First Ticket
                </a>
            </div>
        </div>
    @endif

</div>
@endsection