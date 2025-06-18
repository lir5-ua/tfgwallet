@extends('layouts.app')

@section('content')
<div class="bg-white text-black dark:bg-slate-800 dark:text-white p-4 rounded ease-soft-in-out relative h-full max-h-screen bg-gray-50 transition-all duration-200">
    <div class="w-full px-6 mx-auto dark:text-white">
        <!-- Header -->
        <div class="relative flex items-center p-0 mt-6 overflow-hidden bg-center bg-cover min-h-75 rounded-2xl"
             style="background-image: url('/assets/img/curved-images/curved0.jpg'); background-position-y: 50%">
            <span class="absolute inset-y-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-purple-700 to-pink-500 opacity-60"></span>
        </div>
        <div class="relative flex flex-col flex-auto min-w-0 p-4 mx-6 -mt-16 overflow-hidden break-words border-0 shadow-blur rounded-2xl bg-white/80 bg-clip-border backdrop-blur-2xl backdrop-saturate-200">
            <div class="flex flex-wrap -mx-3">
                <div class="flex-none w-auto max-w-full px-3">
                    <div class="text-base ease-soft-in-out h-18.5 w-18.5 relative inline-flex items-center justify-center rounded-xl text-white transition-all duration-200">
                        <img src="{{ $mascota->imagen_url }}"
                             alt="Foto de mascota"
                             class="w-full shadow-soft-sm rounded-xl object-cover"/>
                    </div>
                </div>
                <div class="flex-none w-auto max-w-full px-3 my-auto">
                    <div class="h-full">
                        <h5 class="mb-1">Detalle de Entrada Médica</h5>
                        <p class="mb-0 text-sm text-slate-600">Información de {{ $mascota->nombre }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full p-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto lg:w-8/12">
                <div class="relative flex flex-col h-full min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                        <h6 class="mb-0">Información Médica</h6>
                    </div>
                    <div class="flex-auto p-4">
                        <h1 class="text-2xl font-bold mb-4">{{ ucfirst($historial->tipo) }}</h1>
                        <p class="relative z-10 mb-2 leading-normal text-transparent bg-gradient-to-tl from-gray-900 to-slate-800 text-sm bg-clip-text">
                            {{ $mascota->nombre}}</p>
                        <h5 class="mb-2">Veterinario : {{ ucfirst($historial->veterinario) }}</h5>
                        <div class="mb-4 border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <h3 class="text-sm font-semibold text-slate-500 uppercase mb-2">Descripción</h3>
                            <p class="leading-normal text-sm text-slate-700">
                                {{ ucfirst($historial->descripcion) }}
                            </p>
                        </div>
                        <p class="mb-6 leading-normal text-sm">{{ $historial->fecha->format('d/m/Y') }}</p>
                        <div class="flex items-center space-x-4 mt-6">
                            <a href="{{ route('mascotas.historial.edit', [$mascota,$historial]) }}"
                               class="inline-block px-6 py-3 font-bold text-center bg-blue-500 hover:bg-blue-600 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                <i class="fas fa-edit mr-2"></i>
                                Editar
                            </a>
                            <form action="{{ route('mascotas.historial.destroy', [$mascota,$historial]) }}" method="POST"
                                  onsubmit="return confirm('¿Estás seguro de que deseas eliminar este recordatorio?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-block px-6 py-3 font-bold text-center bg-red-500 hover:bg-red-600 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                    <i class="fas fa-trash mr-2"></i>
                                    Eliminar
                                </button>
                            </form>
                            <a href="{{ session('return_to_after_update', route('usuarios.show', auth()->user())) }}"
                               class="inline-block px-6 py-3 font-bold text-center bg-gray-500 hover:bg-gray-400 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Volver
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Información adicional -->
            <div class="w-full max-w-full px-3 mt-6 lg:mt-0 lg:w-4/12 lg:flex-none">
                <div class="relative flex flex-col h-full min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                        <h6 class="mb-0">Información de la Entrada</h6>
                    </div>
                    <div class="flex-auto p-4">
                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-paw text-white text-sm"></i>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-slate-700">Mascota</h6>
                                    <p class="text-sm text-slate-600">{{ $mascota->nombre }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar text-white text-sm"></i>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-slate-700">Fecha</h6>
                                    <p class="text-sm text-slate-600">{{ $historial->fecha->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-stethoscope text-white text-sm"></i>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-slate-700">Tipo</h6>
                                    <p class="text-sm text-slate-600">{{ ucfirst($historial->tipo) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg p-4 text-white">
                            <h6 class="text-sm font-semibold mb-2">Nota</h6>
                            <p class="text-sm opacity-90">Esta es una vista de solo lectura de la entrada médica seleccionada.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
