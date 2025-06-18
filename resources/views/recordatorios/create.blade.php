@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">

    <form action="{{ route('recordatorios.store') }}" method="POST">
        @csrf

        @if ($mascota)
        <input type="hidden" name="mascota_id" value="{{ $mascota->id }}">
        <p class="mb-2 text-gray-700">Mascota seleccionada: <strong>{{ $mascota->nombre }}</strong></p>
        @else
        <label for="mascota_id" class="block text-sm font-medium text-gray-700 mb-1">Selecciona una mascota:</label>
        <select name="mascota_id" id="mascota_id"
                class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-1 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none ">
            @foreach ($mascotas as $m)
            <option value="{{ $m->id }}">{{ $m->nombre }}</option>
            @endforeach
        </select>
        @endif
        <div class="mb-4">
            <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
            <input type="text" name="titulo" id="titulo" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none"
                   required>
        </div>


        <div class="mb-4">
            <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha</label>
            <input
                type="date"
                name="fecha"
                id="fecha"
                min="{{ now()->format('Y-m-d') }}"
                class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-1 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none
                       @error('fecha') border-red-500 @enderror"
                required
            >

            @error('fecha')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                      rows="3"></textarea>
        </div>

        
    
    <button type="submit" class="bg-green-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
            Crear Recordatorio
        </button>
    <a href="{{ session('return_to_after_update', route('usuarios.show', auth()->user())) }}"
       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
        Volver
    </a>
    </form>
</div>
@endsection
