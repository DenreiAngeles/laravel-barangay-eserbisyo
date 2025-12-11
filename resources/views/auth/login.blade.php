<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Barangay e-Serbisyo</title>
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
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">

    <!-- Main Container -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-5xl flex flex-col md:flex-row min-h-[600px]">
        
        <!-- LEFT SIDE: Hero Section with Waves & Checklist -->
        <div class="hidden md:flex md:w-1/2 wave-bg flex-col justify-center relative p-12 text-white">
            
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

            <!-- Content Container (Z-Index ensures it sits above waves) -->
            <div class="relative z-20">
                <!-- HEADER SECTION -->
                <div class="mb-6">
                    <h1 class="text-5xl font-extrabold mb-2 tracking-tight drop-shadow-md leading-tight">
                        Barangay<br>e-Serbisyo
                    </h1>
                </div>
                
                <p class="text-blue-100 text-lg mb-8 font-medium">Your gateway to barangay services</p>

                <!-- CHECKLIST -->
                <div class="space-y-6">
                    <!-- Item 1 -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <!-- Check Icon -->
                            <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center shadow-md">
                                <svg class="w-4 h-4 text-[#1585e1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold">Quick Service Access</h3>
                            <p class="text-blue-100 text-sm opacity-90 leading-snug">File tickets, request documents, and report emergencies instantly</p>
                        </div>
                    </div>

                    <!-- Item 2 -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center shadow-md">
                                <svg class="w-4 h-4 text-[#1585e1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold">Transparent Government</h3>
                            <p class="text-blue-100 text-sm opacity-90 leading-snug">Access budgets, projects, and community announcements</p>
                        </div>
                    </div>

                    <!-- Item 3 -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center shadow-md">
                                <svg class="w-4 h-4 text-[#1585e1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold">Secure & Verified</h3>
                            <p class="text-blue-100 text-sm opacity-90 leading-snug">Your information is protected with verified accounts</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE: Login Form -->
        <div class="w-full md:w-1/2 bg-white p-10 md:p-14 flex flex-col justify-center">
            
            <div class="mb-10">
                <h2 class="text-3xl font-bold text-[#1585e1] mb-2">Login</h2>
                <p class="text-gray-400 text-sm">Welcome! Login to manage your barangay data.</p>
            </div>

            <!-- Error/Success Messages -->
            @if($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-50 border-l-4 border-red-500 text-red-700 text-sm font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-green-50 border-l-4 border-green-500 text-green-700 text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-gray-500 text-xs font-semibold mb-2 uppercase tracking-wide" for="email">
                        Email Address
                    </label>
                    <input class="w-full border-b border-gray-300 py-3 px-1 text-gray-700 leading-tight focus:outline-none focus:border-[#1585e1] transition-colors bg-transparent placeholder-gray-300 font-medium" 
                           id="email" name="email" type="email" placeholder="Enter your email" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-500 text-xs font-semibold mb-2 uppercase tracking-wide" for="password">
                        Password
                    </label>
                    <input class="w-full border-b border-gray-300 py-3 px-1 text-gray-700 leading-tight focus:outline-none focus:border-[#1585e1] transition-colors bg-transparent placeholder-gray-300 font-medium" 
                           id="password" name="password" type="password" placeholder="Enter your password" required>
                </div>

                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center">
                        <input id="remember-me" type="checkbox" class="h-4 w-4 text-[#1585e1] focus:ring-[#1585e1] border-gray-300 rounded cursor-pointer">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-500 cursor-pointer font-medium">
                            Remember me
                        </label>
                    </div>
                    <!-- KEPT: Forgot password link here (near inputs) -->
                    <a href="{{ route('password.request') }}" class="text-xs text-gray-400 hover:text-[#1585e1] transition-colors">Forgot password?</a>
                </div>

                <button class="w-full bg-[#1585e1] hover:bg-blue-600 text-white font-bold py-4 px-4 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200 uppercase tracking-wide text-sm" type="submit">
                    Login
                </button>

                <!-- REMOVED: Duplicate forgot password link from here -->
                <div class="mt-8 text-center text-sm text-gray-500 font-medium">
                    <span>New User? <a href="{{ route('register') }}" class="text-[#1585e1] font-bold hover:text-blue-700 transition-colors">Signup</a></span>
                </div>
            </form>
        </div>
    </div>

</body>
</html>