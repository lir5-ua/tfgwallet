@extends('layouts.gestion')

@section('content')
<div class="container">
    <h1>Usuarios</h1>

    @if (session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <a href="{{ route('usuarios.create') }}">➕ Crear nuevo usuario</a><br><br>
<form method="GET" action="{{ route('usuarios.index') }}" class="mb-4">
    <input type="text" name="busqueda" placeholder="Buscar usuario por nombre" value="{{ request('busqueda') }}"
        class="border border-gray-300 rounded px-3 py-1 mr-2" />
    <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600">
        Buscar
    </button>
</form>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
                <tr>
                    <td>
                        <a href="{{ route('usuarios.mascotas.index', ['usuario' => $usuario->id]) }}">
                            {{ $usuario->name }}
                        </a>
                    </td>

                    <td>{{ $usuario->email }}</td>
                    <td>
                        <a href="{{ route('usuarios.edit', $usuario) }}">✏️ Editar</a> |
                        <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Eliminar este usuario?')">🗑️ Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
<div style="margin-top: 20px; display: flex; justify-content: center;">
    {{ $usuarios->links() }}
</div>

    <br>
    <a href="{{ route('mascotas.index') }}">← Volver a mascotas</a>
</div>
@endsection
