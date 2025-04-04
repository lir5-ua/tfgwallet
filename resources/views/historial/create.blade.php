
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Nueva entrada para {{ $mascota->nombre }}</h2>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mascotas.historial.store', $mascota) }}" method="POST">
        @csrf

        <label>Fecha:</label><br>
        <input type="date" name="fecha" value="{{ old('fecha', now()->format('Y-m-d')) }}"><br><br>

        <label>Tipo:</label><br>
        <input type="text" name="tipo" value="{{ old('tipo') }}"><br><br>

        <label>Veterinario:</label><br>
        <input type="text" name="veterinario" value="{{ old('veterinario') }}"><br><br>

        <label>Descripción:</label><br>
        <textarea name="descripcion">{{ old('descripcion') }}</textarea><br><br>

        <button type="submit">💾 Guardar entrada</button>
    </form>

    <br>
    <a href="{{ route('mascotas.historial.index', $mascota) }}">← Volver al historial</a>
</div>
@endsection
