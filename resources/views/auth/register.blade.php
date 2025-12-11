<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Barangay e-Serbisyo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
        
        /* Smooth Wave Background - Base Blue */
        .wave-bg {
            background-color: #1585e1; 
            position: relative;
            overflow: hidden;
        }
        
        .wave-shape {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            line-height: 0;
        }

        /* Hide scrollbar for form container but allow scrolling */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">

    <!-- Main Container -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-6xl flex flex-col md:flex-row min-h-[700px]">
        
        <!-- LEFT SIDE: Hero Section with Waves -->
        <div class="hidden md:flex md:w-5/12 wave-bg flex-col justify-center relative p-12 text-white">
            
            <!-- Abstract Waves (Red & Yellow Accents) -->
            <div class="absolute bottom-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
                <!-- Red Wave (Layer 1 - Behind) -->
                <svg class="absolute bottom-0 left-0 w-[200%] h-auto z-0" viewBox="0 0 1440 320" style="bottom: 0px; opacity: 0.9;">
                    <path fill="#f45456" fill-opacity="1" d="M0,64L48,80C96,96,192,128,288,128C384,128,480,96,576,90.7C672,85,768,107,864,144C960,181,1056,235,1152,245.3C1248,256,1344,224,1392,208L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                </svg>

                <!-- Yellow Wave (Layer 2 - In Front) -->
                <svg class="absolute bottom-0 left-0 w-[200%] h-auto z-10" viewBox="0 0 1440 320" style="bottom: -20px; opacity: 1;">
                    <path fill="#fcc245" fill-opacity="1" d="M0,192L48,197.3C96,203,192,213,288,229.3C384,245,480,267,576,250.7C672,235,768,181,864,181.3C960,181,1056,235,1152,234.7C1248,235,1344,181,1392,154.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                </svg>
            </div>

            <!-- Content Container -->
            <div class="relative z-20">
                <h1 class="text-5xl font-extrabold mb-2 tracking-tight drop-shadow-md leading-tight">
                    Join Our<br>Community
                </h1>
                
                <p class="text-blue-100 text-lg mb-8 font-medium">Create your verified resident account.</p>

                <!-- Steps Indicator on Left Side -->
                <div class="space-y-6">
                    <!-- Step 1 Indicator -->
                    <div class="flex items-center opacity-100 transition-opacity duration-300" id="indicator-step-1">
                        <div class="w-8 h-8 rounded-full bg-white text-[#1585e1] font-bold flex items-center justify-center shadow-md">1</div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold">Personal Details</h3>
                            <p class="text-blue-100 text-xs">Your basic information</p>
                        </div>
                    </div>

                    <!-- Step 2 Indicator -->
                    <div class="flex items-center opacity-50 transition-opacity duration-300" id="indicator-step-2">
                        <div class="w-8 h-8 rounded-full bg-blue-400/50 text-white font-bold flex items-center justify-center border-2 border-white/30">2</div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold">Valid ID</h3>
                            <p class="text-blue-100 text-xs">Proof of identity</p>
                        </div>
                    </div>

                    <!-- Step 3 Indicator -->
                    <div class="flex items-center opacity-50 transition-opacity duration-300" id="indicator-step-3">
                        <div class="w-8 h-8 rounded-full bg-blue-400/50 text-white font-bold flex items-center justify-center border-2 border-white/30">3</div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold">Selfie Check</h3>
                            <p class="text-blue-100 text-xs">Identity verification</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE: Multi-Step Form -->
        <div class="w-full md:w-7/12 bg-white p-8 md:p-12 flex flex-col relative overflow-y-auto no-scrollbar">
            
            <div class="mb-6">
                <h2 class="text-3xl font-bold text-[#1585e1] mb-1" id="step-title">Get Started</h2>
                <p class="text-gray-400 text-sm" id="step-desc">Please fill in your details below.</p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-6 text-sm font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" enctype="multipart/form-data" id="registration-form">
                @csrf
                
                <!-- STEP 1: CREDENTIALS -->
                <div id="step-1" class="step-section">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-500 text-xs font-semibold mb-1 uppercase">First Name</label>
                            <input class="w-full border-b border-gray-300 py-2 px-1 text-gray-700 focus:outline-none focus:border-[#1585e1] transition-colors bg-transparent placeholder-gray-300" 
                                   type="text" name="firstName" placeholder="Juan" required>
                        </div>
                        <div>
                            <label class="block text-gray-500 text-xs font-semibold mb-1 uppercase">Last Name</label>
                            <input class="w-full border-b border-gray-300 py-2 px-1 text-gray-700 focus:outline-none focus:border-[#1585e1] transition-colors bg-transparent placeholder-gray-300" 
                                   type="text" name="lastName" placeholder="Dela Cruz" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-500 text-xs font-semibold mb-1 uppercase">Birthdate</label>
                            <input class="w-full border-b border-gray-300 py-2 px-1 text-gray-700 focus:outline-none focus:border-[#1585e1] transition-colors bg-transparent placeholder-gray-300" 
                                   type="date" name="birthdate" required>
                        </div>
                        <div>
                            <label class="block text-gray-500 text-xs font-semibold mb-1 uppercase">Civil Status</label>
                            <select class="w-full border-b border-gray-300 py-2 px-1 text-gray-700 focus:outline-none focus:border-[#1585e1] transition-colors bg-white" name="civilStatus" required>
                                <option value="" disabled selected>Select Status</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Widowed">Widowed</option>
                                <option value="Separated">Separated</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-500 text-xs font-semibold mb-1 uppercase">Email Address</label>
                            <input class="w-full border-b border-gray-300 py-2 px-1 text-gray-700 focus:outline-none focus:border-[#1585e1] transition-colors bg-transparent placeholder-gray-300" 
                                   type="email" name="email" placeholder="email@example.com" required>
                        </div>
                        <div>
                            <label class="block text-gray-500 text-xs font-semibold mb-1 uppercase">Contact Number</label>
                            <input class="w-full border-b border-gray-300 py-2 px-1 text-gray-700 focus:outline-none focus:border-[#1585e1] transition-colors bg-transparent placeholder-gray-300" 
                                   type="tel" name="phoneNumber" placeholder="09123456789" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-500 text-xs font-semibold mb-1 uppercase">Complete Address</label>
                        <input class="w-full border-b border-gray-300 py-2 px-1 text-gray-700 focus:outline-none focus:border-[#1585e1] transition-colors bg-transparent placeholder-gray-300" 
                               type="text" name="address" placeholder="House No., Street, Barangay" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-gray-500 text-xs font-semibold mb-1 uppercase">Password</label>
                            <input class="w-full border-b border-gray-300 py-2 px-1 text-gray-700 focus:outline-none focus:border-[#1585e1] transition-colors bg-transparent placeholder-gray-300" 
                                   type="password" name="password" required>
                        </div>
                        <div>
                            <label class="block text-gray-500 text-xs font-semibold mb-1 uppercase">Confirm Password</label>
                            <input class="w-full border-b border-gray-300 py-2 px-1 text-gray-700 focus:outline-none focus:border-[#1585e1] transition-colors bg-transparent placeholder-gray-300" 
                                   type="password" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" onclick="nextStep(2)" class="bg-[#1585e1] hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:shadow-xl transition duration-200 uppercase tracking-wide text-sm">
                            Next &rarr;
                        </button>
                    </div>
                </div>

                <!-- STEP 2: VALID ID -->
                <div id="step-2" class="step-section hidden">
                    <div class="bg-blue-50 border-l-4 border-[#1585e1] p-4 mb-6 rounded-r">
                        <p class="text-sm text-blue-900 font-medium">Please upload a clear photo of a valid Government ID (e.g., National ID, Driver's License, UMID).</p>
                    </div>

                    <!-- Upload Box -->
                    <div class="mb-8 w-full">
                        <label for="validIdInput" class="relative flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition overflow-hidden">
                            
                            <!-- Placeholder Content (Visible by default) -->
                            <div id="validIdPlaceholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                <!-- ID Card Icon -->
                                <svg class="w-16 h-16 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 font-bold">Click to upload Valid ID</p>
                                <p class="text-xs text-gray-400">PNG, JPG (MAX. 5MB)</p>
                            </div>

                            <!-- Image Preview (Hidden by default) -->
                            <img id="validIdPreview" class="hidden absolute inset-0 w-full h-full object-cover" />
                            
                            <!-- Actual Input -->
                            <input id="validIdInput" name="validId" type="file" class="hidden" accept="image/*" onchange="previewFile('validIdInput', 'validIdPreview', 'validIdPlaceholder')" />
                        </label>
                    </div>

                    <div class="flex justify-between">
                        <button type="button" onclick="prevStep(1)" class="text-gray-500 hover:text-gray-700 font-bold py-3 px-6 rounded-lg transition duration-200">
                            &larr; Back
                        </button>
                        <button type="button" onclick="nextStep(3)" class="bg-[#1585e1] hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:shadow-xl transition duration-200 uppercase tracking-wide text-sm">
                            Next &rarr;
                        </button>
                    </div>
                </div>

                <!-- STEP 3: SELFIE -->
                <div id="step-3" class="step-section hidden">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r">
                        <p class="text-sm text-yellow-800 font-medium">Take a selfie holding your Valid ID. Ensure your face and the ID details are clearly visible.</p>
                    </div>

                    <!-- Upload Box -->
                    <div class="mb-8 w-full">
                        <label for="selfieInput" class="relative flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition overflow-hidden">
                            
                            <!-- Placeholder Content -->
                            <div id="selfiePlaceholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                <!-- Face Icon -->
                                <svg class="w-16 h-16 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 font-bold">Click to upload Selfie</p>
                                <p class="text-xs text-gray-400">PNG, JPG (MAX. 5MB)</p>
                            </div>

                            <!-- Image Preview -->
                            <img id="selfiePreview" class="hidden absolute inset-0 w-full h-full object-cover" />
                            
                            <!-- Actual Input -->
                            <input id="selfieInput" name="selfie" type="file" class="hidden" accept="image/*" onchange="previewFile('selfieInput', 'selfiePreview', 'selfiePlaceholder')" />
                        </label>
                    </div>

                    <div class="flex justify-between items-center">
                        <button type="button" onclick="prevStep(2)" class="text-gray-500 hover:text-gray-700 font-bold py-3 px-6 rounded-lg transition duration-200">
                            &larr; Back
                        </button>
                        <button type="submit" class="bg-[#1585e1] hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:shadow-xl transition duration-200 w-1/2 uppercase tracking-wide text-sm">
                            SUBMIT REGISTRATION
                        </button>
                    </div>
                </div>

                <div class="mt-8 text-center text-sm text-gray-500 font-medium">
                    <span>Already a resident? <a href="{{ route('login') }}" class="text-[#1585e1] font-bold hover:text-blue-700 transition-colors">Login</a></span>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Multi-step Form & Image Preview -->
    <script>
        let currentStep = 1;

        function showStep(step) {
            // Hide all steps
            document.querySelectorAll('.step-section').forEach(el => el.classList.add('hidden'));
            // Show target step
            document.getElementById('step-' + step).classList.remove('hidden');
            
            // Update Text Header
            const titles = ["Get Started", "Verify Identity", "Final Step"];
            const descs = ["Please fill in your details below.", "We need to verify it's really you.", "One last photo to complete verification."];
            
            document.getElementById('step-title').innerText = titles[step - 1];
            document.getElementById('step-desc').innerText = descs[step - 1];

            // Update Indicators (Left Panel)
            for(let i=1; i<=3; i++) {
                const indicator = document.getElementById('indicator-step-' + i);
                if (i === step) {
                    indicator.classList.remove('opacity-50');
                    indicator.classList.add('opacity-100', 'scale-105');
                    indicator.querySelector('div').classList.remove('bg-blue-400/50', 'text-white', 'border-2');
                    indicator.querySelector('div').classList.add('bg-white', 'text-[#1585e1]');
                } else if (i < step) {
                    // Completed steps
                    indicator.classList.remove('opacity-100', 'scale-105');
                    indicator.classList.add('opacity-50');
                } else {
                    // Future steps
                    indicator.classList.remove('opacity-100', 'scale-105');
                    indicator.classList.add('opacity-50');
                    indicator.querySelector('div').classList.add('bg-blue-400/50', 'text-white', 'border-2');
                    indicator.querySelector('div').classList.remove('bg-white', 'text-[#1585e1]');
                }
            }
            
            currentStep = step;
        }

        function nextStep(targetStep) {
            const currentSection = document.getElementById('step-' + currentStep);
            const inputs = currentSection.querySelectorAll('input, select');
            let valid = true;
            
            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    valid = false;
                }
            });

            // Special check for file inputs if we are moving past them
            if(currentStep === 2) {
                const fileInput = document.getElementById('validIdInput');
                if(fileInput.files.length === 0) {
                    alert("Please upload a Valid ID to proceed.");
                    valid = false;
                }
            }

            if (valid) {
                showStep(targetStep);
            }
        }

        function prevStep(targetStep) {
            showStep(targetStep);
        }

        // Updated Image Preview Logic
        function previewFile(inputId, imgId, placeholderId) {
            const preview = document.getElementById(imgId);
            const placeholder = document.getElementById(placeholderId);
            const file = document.getElementById(inputId).files[0];
            const reader = new FileReader();

            reader.addEventListener("load", function () {
                // Show image
                preview.src = reader.result;
                preview.classList.remove('hidden');
                
                // Hide placeholder content
                placeholder.classList.add('hidden');
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>

</body>
</html>