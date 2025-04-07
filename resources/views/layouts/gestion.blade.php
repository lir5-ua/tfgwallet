<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión</title>
    @if (auth()->check())
        <div style="text-align: right; padding: 10px;">
            <strong>{{ auth()->user()->name }}</strong><br>
            <span style="font-size: 0.9em; color: gray;">
                {{ auth()->user()->is_admin ? 'Administrador' : 'Usuario' }}
            </span>


        </div>
    @endif

</head>
<body>

    {{-- Header específico para sección de gestión --}}
    <header style="background-color: #f8f9fa; padding: 1rem; border-bottom: 1px solid #ccc;">
        <nav style="display: flex; gap: 2rem;">
            @auth
                @if (auth()->user()->is_admin)
                    <a href="{{ route('usuarios.index') }}">Usuarios</a>
                @endif
            <a href="{{ route('mascotas.index') }}">Mascotas</a>
            @if (!auth()->user()->is_admin)
                    <a href="{{ route('recordatorios.index') }}">Recordatorios</a>
                @endif
            @endauth

        </nav>

    </header>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

<a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    Cerrar sesión
</a>

    {{-- Contenido principal --}}
    <main style="padding: 2rem;">
        @yield('content')
    </main>

</body>
</html>
