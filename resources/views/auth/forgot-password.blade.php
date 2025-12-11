<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Barangay e-Serbisyo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
        .wave-bg {
            background-color: #1585e1; 
            position: relative;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">

    <!-- Main Container -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-4xl flex flex-col md:flex-row min-h-[500px]">
        
        <!-- LEFT SIDE: Waves -->
        <div class="hidden md:flex md:w-1/2 wave-bg flex-col justify-center relative p-12 text-white">
            <div class="absolute bottom-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
                <svg class="absolute bottom-0 left-0 w-[200%] h-auto z-0" viewBox="0 0 1440 320" style="bottom: 0px; opacity: 0.9;">
                    <path fill="#f45456" fill-opacity="1" d="M0,64L48,80C96,96,192,128,288,128C384,128,480,96,576,90.7C672,85,768,107,864,144C960,181,1056,235,1152,245.3C1248,256,1344,224,1392,208L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                </svg>
                <svg class="absolute bottom-0 left-0 w-[200%] h-auto z-10" viewBox="0 0 1440 320" style="bottom: -20px; opacity: 1;">
                    <path fill="#fcc245" fill-opacity="1" d="M0,192L48,197.3C96,203,192,213,288,229.3C384,245,480,267,576,250.7C672,235,768,181,864,181.3C960,181,1056,235,1152,234.7C1248,235,1344,181,1392,154.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                </svg>
            </div>

            <div class="relative z-20 text-center">
                <h1 class="text-4xl font-extrabold mb-4 drop-shadow-md">Account<br>Recovery</h1>
                <p class="text-blue-100 text-sm font-medium">
                    Don't worry, it happens to the best of us. We'll help you get back in.
                </p>
            </div>
        </div>

        <!-- RIGHT SIDE: Reset Form -->
        <div class="w-full md:w-1/2 bg-white p-10 md:p-12 flex flex-col justify-center">
            
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-[#1585e1] mb-2">Forgot Password?</h2>
                <p class="text-gray-400 text-sm">Enter your email to receive a reset link.</p>
            </div>

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

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                
                <div class="mb-8">
                    <label class="block text-gray-500 text-xs font-semibold mb-2 uppercase tracking-wide" for="email">
                        Email Address
                    </label>
                    <input class="w-full border-b border-gray-300 py-3 px-1 text-gray-700 leading-tight focus:outline-none focus:border-[#1585e1] transition-colors bg-transparent placeholder-gray-300 font-medium" 
                           id="email" name="email" type="email" placeholder="Enter your registered email" required>
                </div>

                <button class="w-full bg-[#1585e1] hover:bg-blue-600 text-white font-bold py-4 px-4 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200 uppercase tracking-wide text-sm" type="submit">
                    Send Reset Link
                </button>

                <div class="mt-8 text-center text-sm text-gray-500 font-medium">
                    <a href="{{ route('login') }}" class="text-gray-400 hover:text-[#1585e1] transition-colors flex items-center justify-center gap-2">
                        <span>&larr;</span> Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>