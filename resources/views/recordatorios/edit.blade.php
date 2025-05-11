@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow-md rounded-xl p-6 mt-10">
    <h1 class="text-2xl font-bold mb-6">Editar Recordatorio</h1>

    @if ($errors->any())
    <div class="mb-4 text-red-600">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('recordatorios.update', $recordatorio) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Título -->
        <div class="mb-4">
            <label for="titulo" class="block text-sm font-semibold text-gray-700">Título</label>
            <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $recordatorio->titulo) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Descripción -->
        <div class="mb-4">
            <label for="descripcion" class="block text-sm font-semibold text-gray-700">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="4"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('descripcion', $recordatorio->descripcion) }}</textarea>
        </div>

        <!-- Fecha -->
        <div class="mb-4">
            <label for="fecha" class="block text-sm font-semibold text-gray-700">Fecha</label>
            <input type="date" name="fecha" id="fecha" value="{{ old('fecha', $recordatorio->fecha->format('Y-m-d')) }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ url()->previous() }}"
               class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded">
                ← Volver
            </a>

            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                Guardar cambios
            </button>
        </div>
    </form>
</div>
@endsection
