@vite('resources/css/app.css')
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- AOS CSS -->
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>




<body class="bg-gray-100">
    <!-- Navbar/topbar -->
    
    <main class="mb-16">
        @yield('content')
    </main>

    <!-- Bottom bar disini -->
    <nav class="fixed bottom-0 inset-x-0 z-40 bg-white border-t border-gray-200 shadow-inner">
        <div class="flex justify-between items-center px-6 relative h-16">
            
            <!-- Home -->
            <a href="{{ route("siswa.home") }}" class="flex flex-col items-center text-sm w-1/8 {{ request()->routeIs('home') ? 'text-blue-600' : 'text-gray-600' }} hover:text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path fill="currentColor" d="M10 19v-5h4v5c0 .55.45 1 1 1h3c.55 0 1-.45 1-1v-7h1.7c.46 0 .68-.57.33-.87L12.67 3.6c-.38-.34-.96-.34-1.34 0l-8.36 7.53c-.34.3-.13.87.33.87H5v7c0 .55.45 1 1 1h3c.55 0 1-.45 1-1"/>
                </svg>
                Home
            </a>
    
            <!-- Request -->
            <a href="{{ route('siswa.request') }}" class="flex flex-col items-center text-sm w-1/5 {{ request()->routeIs('request') ? 'text-blue-600' : 'text-gray-600' }} hover:text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path fill="currentColor" d="M3 7c-.6 0-1 .4-1 1s.4 1 1 1h2V7zm-1 4c-.6 0-1 .4-1 1s.4 1 1 1h3v-2zm-1 4c-.6 0-1 .4-1 1s.4 1 1 1h4v-2zM20 5H9c-1.1 0-2 .9-2 2v14l4-4h9c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2"/>                </svg>
                Request
            </a>
    
            <!-- Absen (Floating in Center) -->
            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                <a href="{{ route("siswa.dashboard") }}" class="bg-blue-600 text-white p-4 rounded-full shadow-lg hover:bg-blue-700 transition duration-300 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path fill="currentColor" d="M3 11h8V3H3zm2-6h4v4H5zM3 21h8v-8H3zm2-6h4v4H5zm8-12v8h8V3zm6 6h-4V5h4zm-5.99 4h2v2h-2zm2 2h2v2h-2zm-2 2h2v2h-2zm4 0h2v2h-2zm2 2h2v2h-2zm-4 0h2v2h-2zm2-6h2v2h-2zm2 2h2v2h-2z"/>                    </svg>
                </a>
            </div>
    
            <!-- News -->
            <a href="{{ route('siswa.berita') }}" class="flex flex-col items-center text-sm w-1/5 {{ request()->routeIs('news') ? 'text-blue-600' : 'text-gray-600' }} hover:text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path fill="currentColor" fill-rule="evenodd" d="M18 4v3h3a1 1 0 0 1 1 1v10a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1m2 14a1 1 0 1 1-2 0V9h2zM6 8a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H7a1 1 0 0 1-1-1m2 4a1 1 0 0 1 1-1h4a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1" clip-rule="evenodd"/>
                </svg>
              News
            </a>
    
            <!-- Profile -->
            <a href="{{ route('siswa.profile') }}" class="flex flex-col items-center text-sm w-1/8 {{ request()->routeIs('profile') ? 'text-blue-600' : 'text-gray-600' }} hover:text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path fill="currentColor" fill-rule="evenodd" d="M8 7a4 4 0 1 1 8 0a4 4 0 0 1-8 0m0 6a5 5 0 0 0-5 5a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3a5 5 0 0 0-5-5z" clip-rule="evenodd"/>
                </svg>
                Profile
            </a>
    
        </div>
    </nav>
    


</body>


<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init();
</script>
