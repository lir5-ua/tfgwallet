
@extends('layouts.gestion')

@if (isset($recordatoriosHoy) && $recordatoriosHoy->isNotEmpty())
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded">
        <p class="font-bold">¡Tienes recordatorios para hoy!</p>
        <ul class="list-disc pl-5 mt-2">
           @foreach ($recordatoriosHoy as $recordatorio)
             <li>  <a href="{{ route('recordatorios.show', $recordatorio) }}" class="block text-blue-600 hover:underline">
                   {{ $recordatorio->mascota->nombre }}: {{ $recordatorio->titulo }}
               </a>
                    <form action="{{ route('recordatorios.visto', $recordatorio) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-blue-600 hover:underline">👁️ Visto</button>
                    </form>
                            </li>
           @endforeach

        </ul>
    </div>
@endif

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold">{{ $titulo }}</h1>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <a href="{{ route('mascotas.create') }}">➕ Nueva Mascota</a>
<form method="GET" action="{{ route('mascotas.index') }}">
    <input type="text" name="busqueda" placeholder="Buscar por nombre" value="{{ request('busqueda') }}">
    <button type="submit">Buscar</button>
     <a href="{{ route('mascotas.index') }}">
            <button type="button">Resetear</button>
        </a>
</form>
    <table border="1" cellpadding="8" cellspacing="0" style="margin-top: 15px;">
        <thead>
            <tr>
                <th><a href="{{ route('mascotas.index', ['ordenar' => 'nombre', 'direccion' => request('direccion') === 'asc' ? 'desc' : 'asc']) }}">Nombre</a></th>
                <th><a href="{{ route('mascotas.index', ['ordenar' => 'especie', 'direccion' => request('direccion') === 'asc' ? 'desc' : 'asc']) }}">Especie</a></th>
                <th>Raza</th>
                <th><a href="{{ route('mascotas.index', ['ordenar' => 'sexo', 'direccion' => request('direccion') === 'asc' ? 'desc' : 'asc']) }}">Sexo</a></th>
                <th>Usuario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mascotas as $mascota)
                <tr>
                    <td>
                        <a href="{{ route('mascotas.show', $mascota) }}">
                            {{ $mascota->nombre }}
                        </a>
                    </td>
                    <td>{{ ucfirst($mascota->especie->value) }}</td>
                    <td>{{ $mascota->raza }}</td>
                    <td>{{ $mascota->sexo?->value }}</td>
                    <td>{{ $mascota->usuario->name ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('mascotas.edit', $mascota) }}">✏️ Editar</a> |
                        <form action="{{ route('mascotas.destroy', $mascota) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Seguro que quieres eliminar esta mascota?')">🗑️ Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
<div style="margin-top: 20px; display: flex; justify-content: center;">
    {{ $mascotas->links() }}
</div>


</div>
@endsection
