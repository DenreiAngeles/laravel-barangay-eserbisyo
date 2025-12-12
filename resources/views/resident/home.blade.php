@extends('layouts.app')

@section('title', 'Home')
@section('page-title', 'Home')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- ANNOUNCEMENTS SECTION -->
    <section class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Announcements</h2>
            <a href="#" class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center">
                View All <i class="fas fa-chevron-right ml-2 text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- Announcement Card 1 -->
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="font-semibold text-gray-800 line-clamp-1">Community Clean-Up Drive</h3>
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">event</span>
                </div>
                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                    Join us this Saturday, October 15, 2025, for a community clean-up drive. Meeting point at the barangay hall at 6:00 AM. Please brin...
                </p>
                <span class="text-xs text-gray-400">2025-10-08</span>
            </div>

            <!-- Announcement Card 2 -->
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="font-semibold text-gray-800 line-clamp-1">Typhoon Warning Alert</h3>
                    <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full font-medium">emergency</span>
                </div>
                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                    PAGASA has issued a typhoon warning for our area. Expected landfall on October 12. Residents in low-lying areas are advised ...
                </p>
                <span class="text-xs text-gray-400">2025-10-09</span>
            </div>

            <!-- Announcement Card 3 -->
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="font-semibold text-gray-800 line-clamp-1">Free Medical Mission</h3>
                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">announcement</span>
                </div>
                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                    Free medical check-up and medicines will be provided on October 20 at the barangay health center. Services include general...
                </p>
                <span class="text-xs text-gray-400">2025-10-07</span>
            </div>

        </div>
    </section>

    <!-- QUICK ACTIONS SECTION -->
    <section class="mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- New Ticket -->
            <a href="{{ route('resident.tickets') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-300 transition group">
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-blue-200 transition">
                    <i class="fas fa-ticket-alt text-2xl text-blue-600"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">New Ticket</h3>
                <p class="text-xs text-gray-500">Report issues or concerns</p>
            </a>

            <!-- Request Document -->
            <a href="{{ route('resident.documents') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-green-300 transition group">
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-green-200 transition">
                    <i class="fas fa-file-alt text-2xl text-green-600"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">Request Document</h3>
                <p class="text-xs text-gray-500">Get barangay certificates</p>
            </a>

            <!-- Barangay Map -->
            <a href="{{ route('resident.map') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-orange-300 transition group">
                <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-orange-200 transition">
                    <i class="fas fa-map text-2xl text-orange-600"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">Barangay Map</h3>
                <p class="text-xs text-gray-500">View area map</p>
            </a>

            <!-- Transparency -->
            <a href="{{ route('resident.transparency') }}" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:border-purple-300 transition group">
                <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-purple-200 transition">
                    <i class="fas fa-clock text-2xl text-purple-600"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">Transparency</h3>
                <p class="text-xs text-gray-500">View budget & projects</p>
            </a>

        </div>
    </section>

    <!-- MY TICKETS SECTION -->
    <section>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">My Tickets</h2>
            <a href="{{ route('resident.tickets') }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center">
                View All <i class="fas fa-chevron-right ml-2 text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- Ticket Card 1 -->
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1">Broken Streetlight on Main Road</h3>
                        <div class="flex items-center space-x-2 text-xs text-gray-500">
                            <span>TKT-001</span>
                            <span>•</span>
                            <span>Infrastructure</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">
                    <div class="flex items-center text-xs text-gray-500">
                        <i class="far fa-calendar mr-1"></i>
                        <span>2025-10-05</span>
                        <span class="mx-2">•</span>
                        <i class="far fa-comments mr-1"></i>
                        <span>3</span>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">Resolved</span>
                </div>
            </div>

            <!-- Ticket Card 2 -->
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1">Stray Dogs in Residential Area</h3>
                        <div class="flex items-center space-x-2 text-xs text-gray-500">
                            <span>TKT-002</span>
                            <span>•</span>
                            <span>Animal Control</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">
                    <div class="flex items-center text-xs text-gray-500">
                        <i class="far fa-calendar mr-1"></i>
                        <span>2025-10-08</span>
                        <span class="mx-2">•</span>
                        <i class="far fa-comments mr-1"></i>
                        <span>5</span>
                    </div>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">In Progress</span>
                </div>
            </div>

            <!-- Ticket Card 3 -->
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1">Blocked Drainage System</h3>
                        <div class="flex items-center space-x-2 text-xs text-gray-500">
                            <span>TKT-003</span>
                            <span>•</span>
                            <span>Sanitation</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">
                    <div class="flex items-center text-xs text-gray-500">
                        <i class="far fa-calendar mr-1"></i>
                        <span>2025-10-09</span>
                        <span class="mx-2">•</span>
                        <i class="far fa-comments mr-1"></i>
                        <span>2</span>
                    </div>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full font-medium">Pending Review</span>
                </div>
            </div>

        </div>
    </section>

</div>
@endsection
