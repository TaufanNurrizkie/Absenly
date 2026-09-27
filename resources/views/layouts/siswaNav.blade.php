@vite('resources/css/app.css')
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- AOS CSS -->
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<link rel="icon" type="image/png" href="{{ asset('img/icb_Logo.png') }}">
<title>SISTEM PRESENSI SISWA CT</title>
@include('partials.pwa-head')

<body class="bg-gray-100">
    <!-- Navbar/topbar -->

    <main>
        @yield('content')
    </main>

    @include('partials.pwa-install-banner')
</body>


<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init();
</script>
