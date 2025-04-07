
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Historial médico de {{ $mascota->nombre }}</h2>

    @if (session('success'
    ))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <a href="{{ route('mascotas.historial.create', $mascota) }}">➕ Nueva entrada</a><br><br>

    @if ($historiales->isEmpty())
        <p>No hay entradas en el historial médico.</p>
    @else
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Veterinario</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($historiales as $h)
                    <tr>
                        <td>{{ $h->fecha->format('Y-m-d') }}</td>
                        <td>{{ $h->tipo }}</td>
                        <td>{{ $h->veterinario }}</td>
                        <td>{{ $h->descripcion }}</td>
                        <td>
                            <a href="{{ route('mascotas.historial.edit', [$mascota, $h]) }}">✏️ Editar</a> |
                            <form action="{{ route('mascotas.historial.destroy', [$mascota, $h]) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Eliminar esta entrada?')">🗑️ Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <br>
    <a href="{{ route('mascotas.index') }}">← Volver a mascotas</a>
</div>
@endsection
