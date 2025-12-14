@extends('layouts.app')

@section('title', 'Request Details')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Top Bar -->
    <div class="bg-white border-b border-gray-200 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center gap-4">
            <a href="{{ route('requests.status') }}" class="text-blue-600 hover:text-blue-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-semibold text-gray-900">Request Details</h1>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-6">
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

        <!-- Status Card -->
        <div class="bg-white rounded-2xl p-5 shadow-sm mb-4 border border-gray-200">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-gray-900 font-semibold text-lg">{{ $request['serviceName'] }}</h2>
                    <p class="text-sm text-gray-500">{{ $request['requestID'] }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-medium
                    @if($request['status'] === 'approved') bg-green-100 text-green-800
                    @elseif($request['status'] === 'rejected') bg-red-100 text-red-800
                    @elseif($request['status'] === 'processing') bg-blue-100 text-blue-800
                    @else bg-yellow-100 text-yellow-800
                    @endif">
                    {{ ucfirst(str_replace('_', ' ', $request['status'])) }}
                </span>
            </div>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Category:</span>
                    <span class="text-gray-900 font-medium">{{ $request['serviceCategory'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Date Submitted:</span>
                    <span class="text-gray-900">{{ \Carbon\Carbon::parse($request['dateSubmitted'])->format('M d, Y h:i A') }}</span>
                </div>
                @if(isset($request['processedAt']))
                    <div class="flex justify-between">
                        <span class="text-gray-600">Processed:</span>
                        <span class="text-gray-900">{{ \Carbon\Carbon::parse($request['processedAt'])->format('M d, Y h:i A') }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Request Information -->
        <div class="bg-white rounded-2xl p-5 shadow-sm mb-4 border border-gray-200">
            <h3 class="text-gray-900 font-semibold mb-3">Request Information</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-600 mb-1">Name</p>
                    <p class="text-gray-900">{{ $request['residentName'] }}</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-1">Contact Number</p>
                    <p class="text-gray-900">{{ $request['contactNumber'] }}</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-1">Email</p>
                    <p class="text-gray-900">{{ $request['email'] }}</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-1">Purpose</p>
                    <p class="text-gray-900">{{ $request['purpose'] }}</p>
                </div>
            </div>
        </div>

        <!-- Submitted Requirements -->
        <div class="bg-white rounded-2xl p-5 shadow-sm mb-4 border border-gray-200">
            <h3 class="text-gray-900 font-semibold mb-3">Submitted Requirements</h3>
            <div class="space-y-3">
                @foreach($request['submittedRequirements'] ?? [] as $index => $req)
                    <div class="border border-gray-200 rounded-xl p-4">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <p class="text-gray-900 font-medium">{{ $req['requirementName'] }}</p>
                                <p class="text-sm text-gray-500">{{ $req['fileName'] }}</p>
                            </div>
                            <span class="px-2 py-1 rounded-lg text-xs font-medium ml-2
                                @if($req['approvalStatus'] === 'approved') bg-green-100 text-green-800
                                @elseif($req['approvalStatus'] === 'rejected') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($req['approvalStatus']) }}
                            </span>
                        </div>

                        @if(isset($req['note']) && $req['note'])
                            <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                <p class="text-sm text-gray-700">{{ $req['note'] }}</p>
                            </div>
                        @endif

                        <div class="flex items-center gap-3">
                            <a href="{{ $req['fileUrl'] }}" target="_blank" 
                               class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                View File
                            </a>

                            @if($req['approvalStatus'] === 'rejected' && $request['status'] !== 'approved')
                                <button onclick="openResubmitModal({{ $index }}, '{{ $req['requirementName'] }}')"
                                        class="text-orange-600 hover:text-orange-700 text-sm font-medium">
                                    Resubmit
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Rejection Reason (if rejected) -->
        @if($request['status'] === 'rejected' && isset($request['rejectionReason']))
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-4">
                <h4 class="text-red-900 font-medium mb-2">Rejection Reason</h4>
                <p class="text-red-800 text-sm">{{ $request['rejectionReason'] }}</p>
            </div>
        @endif

        <!-- Success Message (if approved) -->
        @if($request['status'] === 'approved')
            <div class="bg-green-50 border border-green-200 rounded-2xl p-4 mb-4">
                <h4 class="text-green-900 font-medium mb-2">✓ Request Approved</h4>
                <p class="text-green-800 text-sm">
                    Your request has been approved. Please wait for further instructions via email or SMS.
                    @if($request['serviceCategory'] === 'Documents')
                        You may claim your document at the barangay office during office hours.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>

<!-- Resubmit Modal -->
<div id="resubmitModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full">
        <h3 class="text-gray-900 font-semibold text-lg mb-4">Resubmit Requirement</h3>
        <p id="requirementName" class="text-gray-600 mb-4"></p>
        
        <form id="resubmitForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload New File</label>
                <input type="file" name="file" accept=".jpg,.jpeg,.png,.pdf" required
                       class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">Accepted: JPG, PNG, PDF (max 5MB)</p>
            </div>
            
            <div class="flex gap-3">
                <button type="button" onclick="closeResubmitModal()"
                        class="flex-1 px-4 py-2 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openResubmitModal(index, name) {
    document.getElementById('requirementName').textContent = name;
    document.getElementById('resubmitForm').action = "{{ route('requests.resubmit', ['requestId' => $request['id'], 'index' => '__INDEX__']) }}".replace('__INDEX__', index);
    document.getElementById('resubmitModal').classList.remove('hidden');
}

function closeResubmitModal() {
    document.getElementById('resubmitModal').classList.add('hidden');
    document.getElementById('resubmitForm').reset();
}

// Close modal when clicking outside
document.getElementById('resubmitModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeResubmitModal();
    }
});
</script>
@endpush
@endsection