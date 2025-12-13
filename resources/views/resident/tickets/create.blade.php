@extends('layouts.app')

@section('title', 'Create New Ticket')
@section('page-title', 'Create New Ticket')

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- Back Button -->
    <a href="{{ route('resident.tickets') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 mb-6 transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Tickets
    </a>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700 font-medium">{{ $errors->first() }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200">
        
        <!-- Header -->
        <div class="px-8 py-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Submit a New Ticket</h2>
            <p class="text-gray-500 text-sm mt-1">Fill in the details below to report an issue or request assistance</p>
        </div>

        <!-- Form -->
        <form action="{{ route('resident.tickets.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf

            <!-- Title -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="title">
                    Title <span class="text-red-500">*</span>
                </label>
                <input 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    id="title" 
                    name="title" 
                    type="text" 
                    placeholder="Brief description of the issue" 
                    value="{{ old('title') }}"
                    required
                >
                <p class="text-xs text-gray-500 mt-1">Provide a clear and concise title for your ticket</p>
            </div>

            <!-- Category & Location Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                <!-- Category -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="category">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        id="category" 
                        name="category"
                        required
                    >
                        <option value="" disabled selected>Select a category</option>
                        <option value="Infrastructure" {{ old('category') === 'Infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                        <option value="Sanitation" {{ old('category') === 'Sanitation' ? 'selected' : '' }}>Sanitation</option>
                        <option value="Animal Control" {{ old('category') === 'Animal Control' ? 'selected' : '' }}>Animal Control</option>
                        <option value="Noise Complaint" {{ old('category') === 'Noise Complaint' ? 'selected' : '' }}>Noise Complaint</option>
                        <option value="Public Safety" {{ old('category') === 'Public Safety' ? 'selected' : '' }}>Public Safety</option>
                        <option value="Health" {{ old('category') === 'Health' ? 'selected' : '' }}>Health</option>
                        <option value="Emergency" {{ old('category') === 'Emergency' ? 'selected' : '' }}>Emergency</option>
                        <option value="Other" {{ old('category') === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Location -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="location">
                        Location <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition pr-12"
                            id="location" 
                            name="location" 
                            type="text" 
                            placeholder="Enter location" 
                            value="{{ old('location') }}"
                            required
                        >
                        <button type="button" onclick="useCurrentLocation()" class="absolute right-3 top-1/2 -translate-y-1/2 text-blue-600 hover:text-blue-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                    <button type="button" onclick="useCurrentLocation()" class="text-xs text-blue-600 hover:text-blue-700 mt-1 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        Use my current location
                    </button>
                </div>

            </div>

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="description">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-y"
                    id="description" 
                    name="description" 
                    rows="5" 
                    placeholder="Provide detailed information about the issue..."
                    required
                >{{ old('description') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Include as much detail as possible to help us address your concern</p>
            </div>

            <!-- Collaborators (Optional) -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="collaborators">
                    Add Collaborators (Optional)
                </label>
                <input 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    id="collaborators" 
                    name="collaborators" 
                    type="text" 
                    placeholder="Enter names or IDs"
                    value="{{ old('collaborators') }}"
                >
                <p class="text-xs text-gray-500 mt-1">Add other residents who can contribute to this ticket</p>
            </div>

            <!-- Attachments -->
            <div class="mb-8">
                <label class="block text-gray-700 text-sm font-semibold mb-3">
                    Attachments
                </label>
                <p class="text-xs text-gray-500 mb-3">Photos and videos help us understand the issue better</p>

                <div class="grid grid-cols-2 gap-4">
                    
                    <!-- Take Photo -->
                    <label class="cursor-pointer">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 hover:bg-blue-50 transition">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-700">Take Photo</p>
                            <p class="text-xs text-gray-500 mt-1">Camera</p>
                        </div>
                        <input type="file" name="attachments[]" accept="image/*" capture="environment" class="hidden" onchange="previewFiles(this)" multiple>
                    </label>

                    <!-- Upload File -->
                    <label class="cursor-pointer">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 hover:bg-blue-50 transition">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-700">Upload File</p>
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG (Max 5MB)</p>
                        </div>
                        <input type="file" name="attachments[]" accept="image/*,.pdf,.doc,.docx" class="hidden" onchange="previewFiles(this)" multiple>
                    </label>

                </div>

                <!-- File Preview Area -->
                <div id="filePreview" class="mt-4 grid grid-cols-4 gap-2 hidden">
                    <!-- Previews will be inserted here -->
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('resident.tickets') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-lg hover:shadow-xl transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Submit Ticket
                </button>
            </div>

        </form>

    </div>

</div>

<script>
function useCurrentLocation() {
    const locationInput = document.getElementById('location');
    
    if (navigator.geolocation) {
        locationInput.value = 'Getting location...';
        
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                locationInput.value = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
            },
            (error) => {
                locationInput.value = '';
                alert('Unable to get your location. Please enter it manually.');
            }
        );
    } else {
        alert('Geolocation is not supported by your browser.');
    }
}

function previewFiles(input) {
    const previewContainer = document.getElementById('filePreview');
    const files = input.files;
    
    if (files.length > 0) {
        previewContainer.classList.remove('hidden');
        
        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const preview = document.createElement('div');
                preview.className = 'relative group';
                
                if (file.type.startsWith('image/')) {
                    preview.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg border border-gray-200">
                        <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <span class="text-white text-xs font-medium">${file.name}</span>
                        </div>
                    `;
                } else {
                    preview.innerHTML = `
                        <div class="w-full h-24 bg-gray-100 rounded-lg border border-gray-200 flex flex-col items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-xs text-gray-500 mt-1">${file.name.substring(0, 10)}...</span>
                        </div>
                    `;
                }
                
                previewContainer.appendChild(preview);
            };
            
            reader.readAsDataURL(file);
        });
    }
}
</script>
@endsection