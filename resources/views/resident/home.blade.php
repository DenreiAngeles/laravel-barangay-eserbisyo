@extends('layouts.app')

@section('title', 'Home')
@section('page-title', 'Home')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <!-- ANNOUNCEMENTS SECTION -->
    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-bullhorn text-blue-600 mr-2"></i>
                Announcements
            </h2>
            <a href="{{ route('resident.transparency') }}#announcements" class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center">
                View All <i class="fas fa-chevron-right ml-2 text-xs"></i>
            </a>
        </div>

        <div id="announcements-container" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Loading state -->
            <div class="col-span-3 text-center py-8">
                <i class="fas fa-spinner fa-spin text-gray-400 text-2xl mb-2"></i>
                <p class="text-gray-500">Loading announcements...</p>
            </div>
        </div>
    </section>

    <!-- QUICK ACTIONS SECTION -->
    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">
            <i class="fas fa-bolt text-purple-600 mr-2"></i>
            Quick Actions
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- New Ticket -->
            <a href="{{ route('resident.tickets') }}" class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200 hover:shadow-md hover:border-blue-300 transition-all group">
                <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-sm">
                    <i class="fas fa-ticket-alt text-2xl text-blue-600"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">New Ticket</h3>
                <p class="text-xs text-gray-600">Report issues or concerns</p>
            </a>

            <!-- Request Document -->
            <a href="{{ route('resident.documents') }}" class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200 hover:shadow-md hover:border-green-300 transition-all group">
                <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-sm">
                    <i class="fas fa-file-alt text-2xl text-green-600"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">Request Document</h3>
                <p class="text-xs text-gray-600">Get barangay certificates</p>
            </a>

            <!-- Barangay Map -->
            <a href="{{ route('resident.map') }}" class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 border border-orange-200 hover:shadow-md hover:border-orange-300 transition-all group">
                <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-sm">
                    <i class="fas fa-map text-2xl text-orange-600"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">Barangay Map</h3>
                <p class="text-xs text-gray-600">View area map</p>
            </a>

            <!-- Transparency -->
            <a href="{{ route('resident.transparency') }}" class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200 hover:shadow-md hover:border-purple-300 transition-all group">
                <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-sm">
                    <i class="fas fa-chart-line text-2xl text-purple-600"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">Transparency</h3>
                <p class="text-xs text-gray-600">View budget & projects</p>
            </a>

        </div>
    </section>

    <!-- MY TICKETS SECTION -->
    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-green-600"></i>
                My Tickets
            </h2>

            <a href="{{ route('resident.tickets') }}"
            class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center">
                View All <i class="fas fa-chevron-right ml-2 text-xs"></i>
            </a>
        </div>

        @if(count($tickets))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach(array_slice($tickets, 0, 3) as $ticket)
                    <a href="{{ route('resident.tickets.show', $ticket['id']) }}"
                    class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-5 border border-gray-200 hover:shadow-lg hover:border-gray-300 transition-all">

                        <h3 class="font-semibold text-gray-800 mb-1 line-clamp-1">
                            {{ $ticket['data']['title'] ?? 'Untitled Ticket' }}
                        </h3>

                        <p class="text-xs text-gray-500 mb-3">
                            {{ $ticket['data']['category'] ?? 'General' }}
                        </p>

                        <div class="flex justify-between items-center text-xs text-gray-400">
                            <span>
                                {{ \Carbon\Carbon::parse($ticket['data']['createdAt'])->format('M d, Y') }}
                            </span>

                            @php
                                $status = strtolower($ticket['data']['status'] ?? 'pending');
                                $badge = match($status) {
                                    'resolved' => 'bg-green-100 text-green-700',
                                    'in-progress' => 'bg-blue-100 text-blue-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    default => 'bg-yellow-100 text-yellow-700'
                                };
                            @endphp

                            <span class="px-3 py-1 rounded-full font-medium {{ $badge }}">
                                {{ ucwords(str_replace('-', ' ', $status)) }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <!-- Empty state (same look, no error now) -->
            <div class="text-center py-10 text-gray-500">
                <i class="fas fa-ticket-alt text-4xl mb-3"></i>
                <p>You haven’t created any tickets yet</p>
            </div>
        @endif
    </section>

</div>

<!-- Firebase Integration Script -->
<script type="module">
    import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js';
    import { getFirestore, collection, query, where, orderBy, limit, getDocs } from 'https://www.gstatic.com/firebasejs/10.8.0/firebase-firestore.js';

    // Firebase configuration
    const firebaseConfig = {
        apiKey: "{{ config('services.firebase.api_key') }}",
        authDomain: "{{ config('services.firebase.auth_domain') }}",
        projectId: "{{ config('services.firebase.project_id') }}",
        storageBucket: "{{ config('services.firebase.storage_bucket') }}",
        messagingSenderId: "{{ config('services.firebase.messaging_sender_id') }}",
        appId: "{{ config('services.firebase.app_id') }}"
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const db = getFirestore(app);

    // Current user ID
    const currentUserId = "{{ auth()->id() }}";

    // Load announcements
    async function loadAnnouncements() {
        const container = document.getElementById('announcements-container');

        try {
            const q = query(
                collection(db, 'announcements'),
                orderBy('createdAt', 'desc'),
                limit(3)
            );

            const querySnapshot = await getDocs(q);

            if (querySnapshot.empty) {
                container.innerHTML = `
                    <div class="col-span-3 text-center py-8">
                        <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">No announcements available</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = '';

            querySnapshot.forEach((doc) => {
                const announcement = doc.data();
                const categoryColors = {
                    'event': 'bg-blue-100 text-blue-700',
                    'emergency': 'bg-red-100 text-red-700',
                    'announcement': 'bg-green-100 text-green-700',
                    'maintenance': 'bg-orange-100 text-orange-700',
                    'general': 'bg-gray-100 text-gray-700'
                };

                const colorClass = categoryColors[announcement.category] || 'bg-gray-100 text-gray-700';
                const date = announcement.createdAt?.toDate ? announcement.createdAt.toDate().toISOString().split('T')[0] : 'N/A';
                const content = announcement.content || announcement.description || '';
                const truncatedContent = content.length > 100 ? content.substring(0, 100) + '...' : content;
                const hasImage = announcement.featuredImageUrl && announcement.featuredImageUrl.trim() !== '';

                let imageHtml = '';
                if (hasImage) {
                    imageHtml = `
                        <div class="mb-3 -mx-5 -mt-5">
                            <img src="${announcement.featuredImageUrl}"
                                 alt="${announcement.title || 'Announcement'}"
                                 class="w-full h-40 object-cover rounded-t-xl"
                                 onerror="this.parentElement.style.display='none'">
                        </div>
                    `;
                }

                container.innerHTML += `
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-5 border border-gray-200 hover:shadow-lg hover:border-gray-300 transition-all overflow-hidden">
                        ${imageHtml}
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-semibold text-gray-800 line-clamp-1 flex-1">${announcement.title || 'Untitled'}</h3>
                            <span class="px-2 py-1 ${colorClass} text-xs rounded-full font-medium ml-2 whitespace-nowrap">${announcement.category || 'general'}</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                            ${truncatedContent}
                        </p>
                        <span class="text-xs text-gray-400">
                            <i class="far fa-calendar mr-1"></i>${date}
                        </span>
                    </div>
                `;
            });

        } catch (error) {
            console.error('Error loading announcements:', error);
            container.innerHTML = `
                <div class="col-span-3 text-center py-8">
                    <i class="fas fa-exclamation-circle text-red-400 text-3xl mb-2"></i>
                    <p class="text-red-600">Error loading announcements</p>
                    <p class="text-sm text-gray-500 mt-1">${error.message}</p>
                </div>
            `;
        }
    }

    /*
    // Load user's tickets (UPDATED TO USE concernsAndEmergencies)
    async function loadTickets() {
        const container = document.getElementById('tickets-container');

        try {
            console.log('🎫 Fetching tickets for user:', currentUserId);

            const q = query(
                collection(db, 'concernsAndEmergencies'),
                where('reportedBy.userId', '==', currentUserId),
                orderBy('dateReported', 'desc'),
                limit(3)
            );

            const querySnapshot = await getDocs(q);
            console.log('✅ Tickets fetched:', querySnapshot.size, 'documents');

            if (querySnapshot.empty) {
                container.innerHTML = `
                    <div class="col-span-3 text-center py-8">
                        <i class="fas fa-ticket-alt text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">You haven't created any tickets yet</p>
                        <a href="{{ route('resident.tickets') }}" class="inline-block mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                            Create Your First Ticket
                        </a>
                    </div>
                `;
                return;
            }

            container.innerHTML = '';

            querySnapshot.forEach((doc) => {
                const ticket = doc.data();

                const statusColors = {
                    'pending': 'bg-yellow-100 text-yellow-700',
                    'in_progress': 'bg-blue-100 text-blue-700',
                    'in-progress': 'bg-blue-100 text-blue-700',
                    'resolved': 'bg-green-100 text-green-700',
                    'rejected': 'bg-red-100 text-red-700',
                    'pending_review': 'bg-orange-100 text-orange-700'
                };

                const statusLabels = {
                    'pending': 'Pending',
                    'in_progress': 'In Progress',
                    'in-progress': 'In Progress',
                    'resolved': 'Resolved',
                    'rejected': 'Rejected',
                    'pending_review': 'Pending Review'
                };

                const colorClass = statusColors[ticket.status] || 'bg-gray-100 text-gray-700';
                const statusLabel = statusLabels[ticket.status] || ticket.status;
                const date = ticket.dateReported?.toDate ? ticket.dateReported.toDate().toISOString().split('T')[0] : 'N/A';
                const commentCount = ticket.commentCount || 0;

                container.innerHTML += `
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-5 border border-gray-200 hover:shadow-lg hover:border-gray-300 transition-all">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1 line-clamp-1">${ticket.concernTitle || ticket.title || 'Untitled'}</h3>
                                <div class="flex items-center space-x-2 text-xs text-gray-500">
                                    <span>${ticket.concernId || ticket.reportId || doc.id}</span>
                                    <span>•</span>
                                    <span>${ticket.concernType || ticket.category || 'General'}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-200">
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="far fa-calendar mr-1"></i>
                                <span>${date}</span>
                                <span class="mx-2">•</span>
                                <i class="far fa-comments mr-1"></i>
                                <span>${commentCount}</span>
                            </div>
                            <span class="px-3 py-1 ${colorClass} text-xs rounded-full font-medium">${statusLabel}</span>
                        </div>
                    </div>
                `;
            });

        } catch (error) {
            console.error('Error loading tickets:', error);

            // Check if it's a permission error
            if (error.code === 'permission-denied' || error.message.includes('permission') || error.message.includes('Missing or insufficient permissions')) {
                container.innerHTML = `
                    <div class="col-span-3 text-center py-8">
                        <i class="fas fa-lock text-red-400 text-3xl mb-2"></i>
                        <p class="text-red-600 font-medium">Permission Error</p>
                        <p class="text-sm text-gray-500 mt-1">Please check browser console for details</p>
                        <p class="text-xs text-gray-400 mt-2">Make sure Firestore rules include 'allow list' for concernsAndEmergencies</p>
                    </div>
                `;
            } else {
                container.innerHTML = `
                    <div class="col-span-3 text-center py-8">
                        <i class="fas fa-exclamation-circle text-red-400 text-3xl mb-2"></i>
                        <p class="text-red-600">Error loading tickets</p>
                        <p class="text-sm text-gray-500 mt-1">${error.message}</p>
                    </div>
                `;
            }
        }
    } */

    // Load data when page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadAnnouncements();
        loadTickets();

        // Handle hash navigation for announcements
        if (window.location.hash === '#announcements') {
            // Small delay to ensure transparency page is ready
            setTimeout(() => {
                const announcementsTab = document.getElementById('tab-announcements');
                if (announcementsTab && typeof switchTab === 'function') {
                    switchTab('announcements');
                }
            }, 100);
        }
    });
</script>
@endsection
