@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar entrada de {{ $mascota->nombre }}</h2>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mascotas.historial.update', [$mascota, $historial]) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Fecha:</label><br>
        <input type="date" name="fecha" value="{{ old('fecha', $historial->fecha->format('Y-m-d')) }}"><br><br>

        <label>Tipo:</label><br>
        <input type="text" name="tipo" value="{{ old('tipo', $historial->tipo) }}"><br><br>

        <label>Veterinario:</label><br>
        <input type="text" name="veterinario" value="{{ old('veterinario', $historial->veterinario) }}"><br><br>

        <label>Descripción:</label><br>
        <textarea name="descripcion">{{ old('descripcion', $historial->descripcion) }}</textarea><br><br>

        <button type="submit">💾 Guardar cambios</button>
    </form>

    <br>
    <a href="{{ session('return_to_after_update', route('usuarios.show', auth()->user())) }}"
       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
        Volver
    </a>
</div>
@endsection
