
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

        <select name="tipo" class="border rounded p-2 text-sm">
            @foreach (App\Enums\TipoHistorial::cases() as $tipo)
            <option value="{{ $tipo->value }}" {{ old('tipo') == $tipo->value ? 'selected' : '' }}>
            {{ $tipo->value }}
            </option>
            @endforeach
        </select><br><br>


        <label>Veterinario:</label><br>
        <input type="text" name="veterinario" value="{{ old('veterinario') }}"><br><br>

        <label>Descripción:</label><br>
        <textarea name="descripcion">{{ old('descripcion') }}</textarea><br><br>

        <button type="submit"class="bg-green-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded"
        >💾 Guardar entrada</button>
    </form>

    <br>
    <a href="{{ session('return_to_after_update', route('usuarios.show', auth()->user())) }}"
       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
        Volver
    </a>
</div>
@endsection
