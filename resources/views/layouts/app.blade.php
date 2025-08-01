<!DOCTYPE html>
<html lang="en"  class="{{ session('modo_oscuro') ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="/storage/petwallet.png" />

    <title>@yield('title', 'PetWallet')</title>

    <!-- Preload critical resources -->
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"></noscript>
    
    <!-- Preload critical CSS -->
    <link rel="preload" href="/assets/css/soft-ui-dashboard-tailwind.css?v=1.0.5" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="/assets/css/soft-ui-dashboard-tailwind.css?v=1.0.5"></noscript>

    <!-- Fonts and icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous" defer></script>
    
    <!-- Nucleo Icons -->
    <link href="/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="/assets/css/nucleo-svg.css" rel="stylesheet" />
    
    <!-- Popper - Loaded asynchronously -->
    <script src="https://unpkg.com/@popperjs/core@2" defer></script>
    
    <!-- Nepcha Analytics - Loaded asynchronously -->
    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
    
    @stack('styles')
</head>

<body class="bg-white text-black dark:bg-slate-800 dark:text-white bg-gray-50 text-slate-700">
<main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200">
    <!-- Sidebar -->
    @auth
    @if (Route::currentRouteName() !== 'register')
    @include('layouts.sidebar')
    @include('layouts.navbar')
    @endif
    @endauth
    
    <!-- Contenido principal -->
    @yield('content')
</main>

    @vite('resources/js/app.js')
    @stack('scripts')
    
    <!-- Load non-critical scripts asynchronously -->
    <script src="/assets/js/plugins/perfect-scrollbar.min.js" async></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="/assets/js/soft-ui-dashboard-tailwind.js?v=1.0.5" async></script>
</body>
</html>
