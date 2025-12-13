@extends('layouts.app')

@section('title', 'Ticket Details')
@section('page-title', 'Ticket Details')

@section('content')
<div class="max-w-6xl mx-auto">

    <!-- Back Button -->
    <a href="{{ route('resident.tickets') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-6 transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Tickets
    </a>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
            <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Main Content (Left) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Ticket Header -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                
                <!-- Status Badge & ID -->
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm text-gray-500 font-medium">{{ $ticket['data']['ticketId'] ?? 'N/A' }}</span>
                    
                    @php
                        $status = strtolower($ticket['data']['status'] ?? 'pending');
                        $badgeClasses = match($status) {
                            'resolved' => 'bg-green-100 text-green-700 border-green-200',
                            'in-progress' => 'bg-blue-100 text-blue-700 border-blue-200',
                            'rejected' => 'bg-red-100 text-red-700 border-red-200',
                            default => 'bg-yellow-100 text-yellow-700 border-yellow-200'
                        };
                        $statusLabel = ucwords(str_replace('-', ' ', $status));
                    @endphp
                    
                    <span class="px-4 py-2 rounded-full text-sm font-semibold border-2 {{ $badgeClasses }}">
                        {{ $statusLabel }}
                    </span>
                </div>

                <!-- Title -->
                <h1 class="text-3xl font-bold text-gray-800 mb-4">
                    {{ $ticket['data']['title'] ?? 'Untitled Ticket' }}
                </h1>

                <!-- Meta Information -->
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-6">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        {{ $ticket['data']['category'] ?? 'General' }}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $ticket['data']['location'] ?? 'No location' }}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ \Carbon\Carbon::parse($ticket['data']['createdAt'])->format('F d, Y g:i A') }}
                    </span>
                </div>

                <!-- Description -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Description</h3>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $ticket['data']['description'] ?? 'No description provided.' }}</p>
                </div>

                <!-- Attachments -->
                @if(isset($ticket['data']['attachments']) && count($ticket['data']['attachments']) > 0)
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">Attachments</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($ticket['data']['attachments'] as $attachment)
                                <a href="{{ $attachment['url'] }}" target="_blank" class="group relative">
                                    @if($attachment['type'] === 'photo')
                                        <img src="{{ $attachment['url'] }}" alt="Attachment" class="w-full h-32 object-cover rounded-lg border-2 border-gray-200 group-hover:border-blue-400 transition">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-full h-32 bg-gray-100 rounded-lg border-2 border-gray-200 group-hover:border-blue-400 transition flex flex-col items-center justify-center p-2">
                                            <svg class="w-12 h-12 text-gray-400 group-hover:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span class="text-xs text-gray-500 mt-2 text-center">{{ $attachment['name'] ?? 'File' }}</span>
                                        </div>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <!-- Comments Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Comments & Updates</h3>

                <!-- Comment List -->
                @if(isset($ticket['data']['comments']) && count($ticket['data']['comments']) > 0)
                    <div class="space-y-4 mb-6">
                        @foreach($ticket['data']['comments'] as $comment)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                            {{ substr($comment['userId'] ?? 'U', 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-semibold text-gray-800">{{ $comment['userId'] === session('firebase_user')['uid'] ? 'You' : 'User' }}</span>
                                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($comment['createdAt'])->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-700">{{ $comment['comment'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No comments yet. Be the first to comment!</p>
                @endif

                <!-- Add Comment Form -->
                <form action="{{ route('resident.tickets.addComment', $ticket['id']) }}" method="POST" class="border-t border-gray-200 pt-6">
                    @csrf
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                {{ substr(session('firebase_user')['email'] ?? 'U', 0, 1) }}
                            </div>
                        </div>
                        <div class="flex-1">
                            <textarea 
                                name="comment" 
                                rows="3" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                placeholder="Add a comment..."
                                required
                            ></textarea>
                            <button type="submit" class="mt-3 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                                Post Comment
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>

        <!-- Sidebar (Right) -->
        <div class="space-y-6">

            <!-- Ticket Info -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Ticket Information</h3>
                
                <div class="space-y-4">
                    <div>
                        <span class="text-xs text-gray-500 uppercase tracking-wide block mb-1">Status</span>
                        <span class="font-semibold text-gray-800">{{ ucwords(str_replace('-', ' ', $ticket['data']['status'] ?? 'Pending')) }}</span>
                    </div>

                    <div>
                        <span class="text-xs text-gray-500 uppercase tracking-wide block mb-1">Priority</span>
                        <span class="font-semibold text-gray-800">{{ ucfirst($ticket['data']['priority'] ?? 'Medium') }}</span>
                    </div>

                    <div>
                        <span class="text-xs text-gray-500 uppercase tracking-wide block mb-1">Created</span>
                        <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($ticket['data']['createdAt'])->format('M d, Y') }}</span>
                    </div>

                    @if(isset($ticket['data']['resolvedAt']) && $ticket['data']['resolvedAt'])
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wide block mb-1">Resolved</span>
                            <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($ticket['data']['resolvedAt'])->format('M d, Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions (Placeholder) -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Actions</h3>
                <button class="w-full mb-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold transition text-sm">
                    Share Ticket
                </button>
                <button class="w-full px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg font-semibold transition text-sm">
                    Delete Ticket
                </button>
            </div>

        </div>

    </div>

</div>
@endsection