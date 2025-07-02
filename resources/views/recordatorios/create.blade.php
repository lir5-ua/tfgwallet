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
                        <svg class="w-full h-full text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-none w-auto max-w-full px-3 my-auto">
                    <div class="h-full">
                        <h5 class="mb-1">Crear Nuevo Recordatorio</h5>
                        <p class="mb-0 text-sm text-slate-600">Programa un recordatorio para el cuidado de tu mascota</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="w-full p-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto lg:w-8/12">
                <div class="relative flex flex-col h-full min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                        <h6 class="mb-0">Información del Recordatorio</h6>
                    </div>
                    
                    <div class="flex-auto p-4">
                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ isset($mascota) ? route('mascotas.recordatorios.store', ['mascota' => $mascota->hashid]) : route('recordatorios.store') }}" method="POST">
                            @csrf

                            <!-- Selección de mascota -->
                            @if ($mascota)
        {{-- **THIS IS THE CRITICAL CHANGE:** Use $mascota->hashid here --}}
        <input type="hidden" name="mascota_id" value="{{ $mascota->hashid }}"> 
        <div class="mb-6 p-4 bg-purple-50 rounded-lg border border-purple-200">
            <div class="flex items-center">
                <img src="{{ $mascota->imagen_url }}"
                        alt="Foto de mascota" 
                        class="w-12 h-12 rounded-lg object-cover mr-3">
                <div>
                    <p class="text-sm text-purple-600 font-medium">Mascota seleccionada:</p>
                    <p class="text-lg font-semibold text-purple-800">{{ $mascota->nombre }}</p>
                </div>
            </div>
        </div>
    @else
        <div class="mb-4">
            <label for="mascota_id" class="block text-sm font-medium text-slate-700 mb-2">
                Selecciona una mascota *
            </label>
            <select name="mascota_id" 
                    id="mascota_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white"
                    required>
                <option value="">-- Selecciona una mascota --</option>
                @foreach ($mascotas as $m)
                    {{-- **AND THIS ONE:** Use $m->hashid here for the dropdown options --}}
                    <option value="{{ $m->hashid }}">{{ $m->nombre }}</option>
                @endforeach
            </select>
        </div>
    @endif

                            <!-- Título -->
                            <div class="mb-4">
                                <label for="titulo" class="block text-sm font-medium text-slate-700 mb-2">
                                    Título del recordatorio *
                                </label>
                                <input type="text" 
                                       name="titulo" 
                                       id="titulo" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                       placeholder="Ej: Vacuna anual, Revisión veterinaria, etc."
                                       required>
                            </div>

                            <!-- Fecha -->
                            <div class="mb-4">
                                <label for="fecha" class="block text-sm font-medium text-slate-700 mb-2">
                                    Fecha del recordatorio *
                                </label>
                                <input type="date" 
                                       name="fecha" 
                                       id="fecha" 
                                       min="{{ now()->format('Y-m-d') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('fecha') border-red-500 @enderror"
                                       required>

                                @error('fecha')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Descripción -->
                            <div class="mb-6">
                                <label for="descripcion" class="block text-sm font-medium text-slate-700 mb-2">
                                    Descripción
                                </label>
                                <textarea name="descripcion" 
                                          id="descripcion" 
                                          rows="4"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                          placeholder="Detalles adicionales sobre el recordatorio..."></textarea>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('recordatorios.index') }}"
                                   class="inline-block px-6 py-3 font-bold text-center bg-gray-500 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Volver
                                </a>
                                <button type="submit" 
                                        class="inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-purple-700 to-pink-500 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                    <i class="fas fa-bell mr-2"></i>
                                    Crear Recordatorio
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="w-full max-w-full px-3 mt-6 lg:mt-0 lg:w-4/12 lg:flex-none">
                <div class="relative flex flex-col h-full min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                        <h6 class="mb-0">Tipos de Recordatorios</h6>
                    </div>
                    
                    <div class="flex-auto p-4">
                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-syringe text-white text-sm"></i>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-slate-700">Vacunas</h6>
                                    <p class="text-sm text-slate-600">Recordatorios de vacunación</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-stethoscope text-white text-sm"></i>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-slate-700">Revisiones</h6>
                                    <p class="text-sm text-slate-600">Visitas al veterinario</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-pills text-white text-sm"></i>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-slate-700">Medicamentos</h6>
                                    <p class="text-sm text-slate-600">Tratamientos y medicinas</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg p-4 text-white">
                            <h6 class="text-sm font-semibold mb-2">Consejo</h6>
                            <p class="text-sm opacity-90">Los recordatorios te ayudarán a mantener un seguimiento del cuidado de tu mascota.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
