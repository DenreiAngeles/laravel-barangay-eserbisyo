@extends('layouts.app')

@section('page-title', $service['name'])

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 py-6 pb-6">
        <!-- Back Button -->
        <a href="{{ route('resident.documents') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 mb-4">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Documents</span>
        </a>
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Service Info Card -->
        <div class="bg-white rounded-2xl p-5 shadow-sm mb-4 border border-gray-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 {{ $service['category'] === 'documents' ? 'bg-blue-100' : 'bg-green-100' }} rounded-xl flex items-center justify-center text-3xl">
                    @if(isset($service['icon']) && $service['icon'])
                        {{ $service['icon'] }}
                    @else
                        {{ $service['category'] === 'documents' ? '📄' : '🎁' }}
                    @endif
                </div>
                <div class="flex-1">
                    <h2 class="text-gray-900 font-semibold">{{ $service['name'] }}</h2>
                    <p class="text-gray-600 text-sm">{{ $service['description'] }}</p>
                </div>
            </div>

            @if(strtolower($service['category']) === 'documents')
                <div class="flex items-center gap-6 pt-3 border-t border-gray-200">
                    <div class="flex items-center gap-2 text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-xs text-gray-600">Fee</p>
                            <p class="font-medium">₱{{ number_format($service['fee'] ?? $service['price'] ?? 0, 0) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-xs text-gray-600">Processing Time</p>
                            <p class="text-sm">{{ $service['processingTime'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="space-y-2 pt-3 border-t border-gray-200">
                    <div>
                        <p class="text-xs text-gray-600">Eligibility</p>
                        <p class="text-sm text-blue-600">{{ $service['eligibility'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Benefits</p>
                        <p class="text-sm text-green-600">{{ $service['benefits'] ?? 'N/A' }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Requirements Card -->
        <div class="bg-white rounded-2xl p-5 shadow-sm mb-4 border border-gray-200">
            <h3 class="text-gray-900 font-semibold mb-3">Requirements</h3>
            <ul class="space-y-2">
                @foreach($service['requirements'] ?? [] as $req)
                    <li class="flex items-start gap-2 text-sm text-gray-700">
                        <span class="text-blue-600 mt-0.5">•</span>
                        <span>{{ is_array($req) ? $req['name'] : $req }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Request Form -->
        <form action="{{ route('resident.documents.request', $service['id']) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="bg-white rounded-2xl p-5 shadow-sm mb-4 border border-gray-200">
                <h3 class="text-gray-900 font-semibold mb-4">Request Details</h3>

                <div class="space-y-4">
                    <!-- Name -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', trim(($sessionUser['firstName'] ?? '') . ' ' . ($sessionUser['lastName'] ?? ''))) }}"
                            required
                            class="w-full h-12 px-4 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition"
                        >
                    </div>

                    <!-- Contact Number -->
                    <div class="space-y-2">
                        <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                        <input 
                            type="text" 
                            id="contact_number" 
                            name="contact_number" 
                            value="{{ old('contact_number', $sessionUser['phoneNumber'] ?? '') }}"
                            required
                            class="w-full h-12 px-4 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition"
                        >
                    </div>

                    <!-- Purpose -->
                    <div class="space-y-2">
                        <label for="purpose" class="block text-sm font-medium text-gray-700">Purpose *</label>
                        <textarea 
                            id="purpose" 
                            name="purpose" 
                            rows="4"
                            placeholder="State the purpose of this request..."
                            required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition resize-none"
                        >{{ old('purpose') }}</textarea>
                    </div>

                    <!-- Supporting Documents -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Supporting Documents *</label>
                        @foreach($service['requirements'] ?? [] as $index => $req)
                            <div class="mb-3">
                                <label class="block text-sm text-gray-600 mb-1">
                                    {{ is_array($req) ? $req['name'] : $req }}
                                </label>
                                <input 
                                    type="file" 
                                    name="requirements[{{ $index }}]"
                                    accept=".jpg,.jpeg,.png,.pdf"
                                    required
                                    class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
                                >
                                <p class="text-xs text-gray-500 mt-1">Accepted: JPG, PNG, PDF (max 5MB)</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Processing Information -->
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6">
                <h4 class="text-blue-900 font-medium mb-2">Processing Information</h4>
                <ul class="space-y-1 text-blue-800 text-sm">
                    @if(strtolower($service['category']) === 'documents')
                        <li>• Processing time: {{ $service['processingTime'] ?? 'N/A' }}</li>
                        <li>• You will be notified once ready for pickup</li>
                        <li>• Please bring valid ID when claiming</li>
                        <li>• Fee: ₱{{ number_format($service['fee'] ?? $service['price'] ?? 0, 0) }} (payable upon pickup)</li>
                    @else
                        <li>• Application will be reviewed within 7-10 business days</li>
                        <li>• You will be contacted for interview if qualified</li>
                        <li>• Please ensure all requirements are complete</li>
                    @endif
                </ul>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium h-12 rounded-xl shadow-md transition-colors"
            >
                Submit Request
            </button>
        </form>
    </div>
</div>
@endsection