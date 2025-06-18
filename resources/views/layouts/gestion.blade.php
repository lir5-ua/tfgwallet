<!DOCTYPE html>
<html lang="es" class="{{ session('modo_oscuro') ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="/assets/img/favicon.png" />

    <title>@yield('title', 'Gestión - PetWallet')</title>

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
    
    @stack('styles')
</head>

<body class="bg-white text-black dark:bg-slate-800 dark:text-white bg-gray-50 text-slate-700">
    <!-- Header específico para sección de gestión -->
    <header class="bg-white dark:bg-slate-700 shadow-soft-xl border-b border-slate-200 dark:border-slate-600">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <h1 class="text-xl font-bold text-slate-800 dark:text-white">Panel de Gestión</h1>
                    <nav class="flex space-x-6">
                        @auth
                            @if (auth()->user()->is_admin)
                                <a href="{{ route('usuarios.index') }}" class="text-slate-600 dark:text-slate-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                                    <i class="fas fa-users mr-2"></i>Usuarios
                                </a>
                            @endif
                            <a href="{{ route('mascotas.index') }}" class="text-slate-600 dark:text-slate-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                                <i class="fas fa-paw mr-2"></i>Mascotas
                            </a>
                            @if (!auth()->user()->is_admin)
                                <a href="{{ route('recordatorios.index') }}" class="text-slate-600 dark:text-slate-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                                    <i class="fas fa-bell mr-2"></i>Recordatorios
                                </a>
                            @endif
                        @endauth
                    </nav>
                </div>
                
                <div class="flex items-center space-x-4">
                    @if (auth()->check())
                        <div class="text-right">
                            <div class="text-sm font-semibold text-slate-800 dark:text-white">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">
                                {{ auth()->user()->is_admin ? 'Administrador' : 'Usuario' }}
                            </div>
                        </div>
                        <div class="w-px h-8 bg-slate-300 dark:bg-slate-600"></div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-600 border border-slate-300 dark:border-slate-500 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-500 transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Cerrar sesión
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Contenido principal -->
    <main class="min-h-screen bg-gray-50 dark:bg-slate-800">
        @yield('content')
    </main>

    @vite('resources/js/app.js')
    @stack('scripts')
    
    <!-- Load non-critical scripts asynchronously -->
    <script src="/assets/js/plugins/perfect-scrollbar.min.js" async></script>
    <script src="/assets/js/soft-ui-dashboard-tailwind.js?v=1.0.5" async></script>
</body>
</html>
