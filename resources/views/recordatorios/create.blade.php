@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Nuevo recordatorio para {{ $mascota->nombre }}</h2>

    <form method="POST" action="{{ route('mascotas.recordatorios.store', $mascota->id) }}">
        @csrf

        <div class="mb-4">
            <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
            <input type="text" name="titulo" id="titulo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
        </div>


        <div class="mb-4">
            <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha</label>
            <input
                type="date"
                name="fecha"
                id="fecha"
                min="{{ now()->format('Y-m-d') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                       @error('fecha') border-red-500 @enderror"
                required
            >

            @error('fecha')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" rows="3"></textarea>
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
            Guardar recordatorio
        </button>
    </form>
</div>
@endsection
