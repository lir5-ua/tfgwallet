@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
        
        <div class="flex-auto px-1 pt-6">
            <h1 class="text-2xl font-bold mb-4">{{ ucfirst($historial->tipo) }}</h1>

            <p class="relative z-10 mb-2 leading-normal text-transparent bg-gradient-to-tl from-gray-900 to-slate-800 text-sm bg-clip-text">
                {{ $mascota->nombre}}</p>
            <h5>Veterinario : {{ ucfirst($historial->veterinario) }}</h5>
            <div class="max-w-full rounded-2xl p-6 bg-white">
                <div class="mb-4 border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <h3 class="text-sm font-semibold text-slate-500 uppercase mb-2">Descripción</h3>
                    <p class="leading-normal text-sm text-slate-700">
                        {{ ucfirst($historial->descripcion) }}
                    </p>
                </div>
            </div>
                <p class="mb-6 leading-normal text-sm">{{ $historial->fecha->format('d/m/Y') }}</p>
           <!-- <p>

                <span class="text-green-600 font-semibold">Realizado</span>

                <span class="text-red-600 font-semibold">Pendiente</span>

            </p>-->
            <div class="flex items-center space-x-4 mt-6">
                <a href="{{ route('mascotas.historial.edit', [$mascota,$historial]) }}"
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Editar</a>

                <form action="{{ route('mascotas.historial.destroy', [$mascota,$historial]) }}" method="POST"
                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar este recordatorio?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                        Eliminar
                    </button>
                </form>

                <a href="{{ session('return_to_after_update', route('usuarios.show', auth()->user())) }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                    Volver
                </a>

            </div>
        </div>
        @endsection
