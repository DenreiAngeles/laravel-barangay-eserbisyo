@extends('layouts.app')

@section('page-title', 'Document Request')

@section('content')
<div class="min-vh-100 bg-light">
    <div class="container-fluid px-4 py-4">
        <div class="mx-auto" style="max-width: 80rem;">

            @if(!$isVerified)
                <!-- Unverified -->
                <div class="flex flex-col items-center justify-center py-20">
                    <div class="rounded-full p-6 mb-4 bg-yellow-100">
                        <i class="bi bi-file-earmark-text text-yellow-600 text-4xl"></i>
                    </div>

                    <h3 class="text-gray-900 text-xl mb-2 font-semibold">
                        Verification Required
                    </h3>

                    <p class="text-gray-600 text-center max-w-sm mb-6">
                        Your account needs to be verified by the barangay admin before you can
                        request documents and apply for benefits.
                    </p>

                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 max-w-sm">
                        <p class="text-blue-800 text-sm">
                            Please wait for admin approval. You will receive a notification once
                            your account is verified.
                        </p>
                    </div>
                </div>
            @else
                <!-- Header with My Requests Button -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div></div>
                    <a href="{{ route('resident.my-requests') }}" class="btn btn-outline-primary rounded-pill px-4">
                        <i class="bi bi-file-text me-2"></i>My Requests
                    </a>
                </div>

                <!-- Tabs -->
                <div class="flex gap-2 mb-6">
                    <button
                        onclick="showTab('documents')"
                        id="tab-documents"
                        class="flex-1 py-2 px-4 rounded-xl font-medium"
                        style="background-color: #2563eb; color: white; border: none;"
                    >
                        Documents
                    </button>

                    <button
                        onclick="showTab('benefits')"
                        id="tab-benefits"
                        class="flex-1 py-2 px-4 rounded-xl font-medium bg-white border border-gray-200"
                        style="color: #374151;"
                    >
                        Benefits
                    </button>
                </div>

                <div id="tab-content">
                    <!-- Documents -->
                    <div id="documents" class="tab-pane">
                        <p class="text-gray-600 mb-4">
                            Request official barangay documents and certificates
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @forelse($documents as $doc)
                                <a
                                    href="{{ route('resident.documents.show', $doc['id']) }}"
                                    class="block bg-white rounded-2xl p-5 shadow-sm border border-gray-200 hover:shadow-md transition"
                                >
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                            @if(isset($doc['icon']) && $doc['icon'])
                                                <span style="font-size: 1.5rem;">{{ $doc['icon'] }}</span>
                                            @else
                                                <i class="bi bi-file-earmark-text text-blue-600 text-2xl"></i>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-gray-900 mb-1 font-semibold">
                                                {{ $doc['name'] }}
                                            </h3>

                                            <p class="text-gray-600 text-sm mb-2">
                                                {{ $doc['description'] }}
                                            </p>

                                            <div class="flex items-center gap-4 text-sm">
                                                <div class="flex items-center gap-1 text-blue-600">
                                                    <i class="bi bi-currency-dollar"></i>
                                                    <span>
                                                        ₱{{ number_format($doc['fee'] ?? $doc['price'] ?? 0, 0) }}
                                                    </span>
                                                </div>

                                                <div class="flex items-center gap-1 text-gray-600">
                                                    <i class="bi bi-clock"></i>
                                                    <span>
                                                        {{ $doc['processingTime'] ?? 'N/A' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-gray-400 self-center">→</div>
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-2 text-center py-5 text-gray-500">
                                    No documents available
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Benefits -->
                    <div id="benefits" class="tab-pane hidden">
                        <p class="text-gray-600 mb-4">
                            Apply for government assistance programs
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @forelse($benefits as $benefit)
                                <a
                                    href="{{ route('resident.documents.show', $benefit['id']) }}"
                                    class="block bg-white rounded-2xl p-5 shadow-sm border border-gray-200 hover:shadow-md transition"
                                >
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                            @if(isset($benefit['icon']) && $benefit['icon'])
                                                <span style="font-size: 1.5rem;">{{ $benefit['icon'] }}</span>
                                            @else
                                                <i class="bi bi-gift text-green-600 text-2xl"></i>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-gray-900 mb-1 font-semibold">
                                                {{ $benefit['name'] }}
                                            </h3>

                                            <p class="text-gray-600 text-sm mb-2">
                                                {{ $benefit['description'] }}
                                            </p>

                                            <div class="flex flex-col gap-1 text-sm">
                                                @if(isset($benefit['eligibility']))
                                                    <span class="text-blue-600">
                                                        {{ $benefit['eligibility'] }}
                                                    </span>
                                                @endif

                                                @if(isset($benefit['benefits']))
                                                    <span class="text-green-600">
                                                        {{ $benefit['benefits'] }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="text-gray-400 self-center">→</div>
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-2 text-center py-5 text-gray-500">
                                    No benefits available
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

<script>
function showTab(tab) {
    // Hide all tabs
    document.getElementById('documents').classList.add('hidden');
    document.getElementById('benefits').classList.add('hidden');

    // Get buttons
    const docsBtn = document.getElementById('tab-documents');
    const benefitsBtn = document.getElementById('tab-benefits');

    // Reset both buttons to white/gray
    docsBtn.style.backgroundColor = 'white';
    docsBtn.style.color = '#374151';
    docsBtn.classList.add('border', 'border-gray-200');
    
    benefitsBtn.style.backgroundColor = 'white';
    benefitsBtn.style.color = '#374151';
    benefitsBtn.classList.add('border', 'border-gray-200');

    // Show selected tab and highlight button
    if (tab === 'documents') {
        document.getElementById('documents').classList.remove('hidden');
        docsBtn.style.backgroundColor = '#2563eb';
        docsBtn.style.color = 'white';
        docsBtn.classList.remove('border', 'border-gray-200');
    } else if (tab === 'benefits') {
        document.getElementById('benefits').classList.remove('hidden');
        benefitsBtn.style.backgroundColor = '#2563eb';
        benefitsBtn.style.color = 'white';
        benefitsBtn.classList.remove('border', 'border-gray-200');
    }
}
</script>
@endsection