<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Home') - Barangay e-Serbisyo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .sidebar-active {
            background-color: #EFF6FF;
            color: #1585e1;
            border-left: 4px solid #1585e1;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col">

            <!-- Logo & Brand -->
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Barangay e-Serbisyo</h2>
                <p class="text-xs text-gray-500 mt-1">Local Government Portal</p>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4">
                <a href="{{ route('resident.home') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-50 transition {{ request()->routeIs('resident.home') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-home w-5"></i>
                    <span class="ml-3 font-medium">Home</span>
                </a>

                <a href="{{ route('resident.tickets') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-50 transition {{ request()->routeIs('resident.tickets') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-ticket-alt w-5"></i>
                    <span class="ml-3 font-medium">Tickets</span>
                </a>

                <a href="{{ route('resident.documents') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-50 transition {{ request()->routeIs('resident.documents') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-file-alt w-5"></i>
                    <span class="ml-3 font-medium">Document Requests</span>
                </a>

                <a href="{{ route('resident.transparency') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-50 transition {{ request()->routeIs('resident.transparency') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-clock w-5"></i>
                    <span class="ml-3 font-medium">Transparency</span>
                </a>

                <a href="{{ route('resident.map') }}" class="flex items-center px-6 py-3 text-gray-600 hover:bg-gray-50 transition {{ request()->routeIs('resident.map') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-map w-5"></i>
                    <span class="ml-3 font-medium">Barangay Map</span>
                </a>
            </nav>

            <!-- Bottom Actions -->
            <div class="border-t border-gray-200 p-4">
                <a href="{{ route('resident.profile') }}" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-lg transition {{ request()->routeIs('resident.profile') ? 'bg-gray-50' : '' }}">
                    <i class="fas fa-user w-5"></i>
                    <span class="ml-3 font-medium">Profile</span>
                </a>

                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg transition">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="ml-3 font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- TOP NAVBAR -->
            <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-8">
                <h1 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Home')</h1>

                <div class="flex items-center space-x-4">
                    <!-- Notification Bell -->
                    <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-full transition">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <!-- User Avatar -->
                    <a href="{{ route('resident.profile') }}" class="flex items-center space-x-2 hover:bg-gray-50 rounded-lg px-3 py-2 transition">
                        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                            {{ substr(session('firebase_user')['email'] ?? 'U', 0, 1) }}
                        </div>
                    </a>
                </div>
            </header>

            <!-- PAGE CONTENT -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-8">
                @yield('content')
            </main>
        </div>

    </div>

    @yield('scripts')
</body>
</html>
