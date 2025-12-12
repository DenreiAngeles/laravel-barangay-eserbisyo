@extends('layouts.app')

@section('title', 'Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="max-w-6xl mx-auto">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- LEFT COLUMN: Digital ID Card -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 relative">
                <!-- ID Header Background -->
                <div class="h-24 bg-gradient-to-r from-[#1585e1] to-[#005bb5]"></div>

                <!-- Profile Picture -->
                <div class="absolute top-12 left-1/2 transform -translate-x-1/2">
                    <div class="h-24 w-24 rounded-full border-4 border-white shadow-md overflow-hidden bg-gray-200">
                        <img src="{{ $user['data']['profilePictureUrl'] ?? $user['data']['selfiePhotoUrl'] ?? 'https://ui-avatars.com/api/?name='.urlencode($user['data']['firstName'] ?? 'User') }}"
                             class="w-full h-full object-cover" alt="Profile">
                    </div>
                </div>

                <div class="pt-16 pb-6 px-6 text-center">
                    <h2 class="text-xl font-bold text-gray-800">
                        {{ $user['data']['firstName'] ?? '' }} {{ $user['data']['lastName'] ?? '' }}
                    </h2>
                    <p class="text-sm text-gray-500 mb-4">{{ $user['data']['email'] ?? '' }}</p>

                    <!-- Verification Badge -->
                    @php
                        $status = $user['data']['verificationStatus'] ?? 'pending';
                        $badgeColor = match($status) {
                            'verified' => 'bg-green-100 text-green-800 border-green-200',
                            'rejected' => 'bg-red-100 text-red-800 border-red-200',
                            default => 'bg-yellow-100 text-yellow-800 border-yellow-200'
                        };
                    @endphp
                    <span class="px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wide border {{ $badgeColor }}">
                        {{ $status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Details & Actions -->
        <div class="md:col-span-2">

            <!-- Personal Information -->
            <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Personal Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs text-gray-400 uppercase">Phone Number</span>
                        <span class="text-gray-700 font-medium">{{ $user['data']['phoneNumber'] ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-400 uppercase">Civil Status</span>
                        <span class="text-gray-700 font-medium">{{ $user['data']['civilStatus'] ?? 'N/A' }}</span>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="block text-xs text-gray-400 uppercase">Address</span>
                        <span class="text-gray-700 font-medium">{{ $user['data']['address'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('resident.documents') }}" class="p-4 bg-blue-50 rounded-xl border border-blue-100 hover:bg-blue-100 transition text-left group">
                    <span class="block text-[#1585e1] font-bold group-hover:text-blue-700">📄 Request Document</span>
                    <span class="text-xs text-blue-400">Clearance, Indigency, etc.</span>
                </a>
                <a href="{{ route('resident.tickets') }}" class="p-4 bg-red-50 rounded-xl border border-red-100 hover:bg-red-100 transition text-left group">
                    <span class="block text-[#f45456] font-bold group-hover:text-red-700">🚨 Report Issue</span>
                    <span class="text-xs text-red-400">File a ticket</span>
                </a>
            </div>

        </div>
    </div>

</div>
@endsection
