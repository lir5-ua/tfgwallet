@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
        <div class="relative">
            <a class="block shadow-xl rounded-2xl">
                <img src="{{ asset('storage/' . ($recordatorio->mascota?->imagen ?? 'default/defaultPet.jpg')) }}"
                     class="max-w-full shadow-soft-2xl rounded-2xl"/>
            </a>
        </div>
        <div class="flex-auto px-1 pt-6">
            <h1 class="text-2xl font-bold mb-4">{{ ucfirst($recordatorio->titulo) }}</h1>

            <p class="relative z-10 mb-2 leading-normal text-transparent bg-gradient-to-tl from-gray-900 to-slate-800 text-sm bg-clip-text">
                {{ $recordatorio->mascota->nombre}}</p>
            <div class="max-w-full rounded-2xl p-6 bg-white">
                <div class="mb-4 border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <h3 class="text-sm font-semibold text-slate-500 uppercase mb-2">Descripción</h3>
                    <p  class="mb-6 leading-normal text-sm">{{ ucfirst($recordatorio->descripcion)}}
                    </p>
                </div>
            </div>
            <p class="mb-6 leading-normal text-sm">{{ $recordatorio->fecha->format('d/m/Y') }}</p>
            <p>
                @if ($recordatorio->realizado)
                <span class="text-green-600 font-semibold">Realizado</span>
                @else
                <span class="text-red-600 font-semibold">Pendiente</span>
                @endif
            </p>
            <div class="flex items-center space-x-4 mt-6">
                <a href="{{ route('recordatorios.edit', $recordatorio) }}"
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Editar
                </a>

                <form action="{{ route('recordatorios.destroy', $recordatorio) }}" method="POST"
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
    </div>
</div>
@endsection
