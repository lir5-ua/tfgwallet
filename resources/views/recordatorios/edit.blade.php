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
                        <h5 class="mb-1">Editar Recordatorio</h5>
                        <p class="mb-0 text-sm text-slate-600">Actualiza la información del recordatorio</p>
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

                        <form action="{{ route('recordatorios.update', $recordatorio) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Información de la mascota -->
                            <div class="mb-6 p-4 bg-purple-50 rounded-lg border border-purple-200">
                                <div class="flex items-center">
                                    <img src="{{ $recordatorio->mascota->imagen_url }}"
                                         alt="Foto de mascota" 
                                         class="w-12 h-12 rounded-lg object-cover mr-3">
                                    <div>
                                        <p class="text-sm text-purple-600 font-medium">Mascota:</p>
                                        <p class="text-lg font-semibold text-purple-800">{{ $recordatorio->mascota->nombre }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Título -->
                            <div class="mb-4">
                                <label for="titulo" class="block text-sm font-medium text-slate-700 mb-2">
                                    Título del recordatorio *
                                </label>
                                <input type="text" 
                                       name="titulo" 
                                       id="titulo" 
                                       value="{{ old('titulo', $recordatorio->titulo) }}"
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
                                       value="{{ old('fecha', $recordatorio->fecha->format('Y-m-d')) }}"
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
                                          placeholder="Detalles adicionales sobre el recordatorio...">{{ old('descripcion', $recordatorio->descripcion) }}</textarea>
                            </div>
                            @if($recordatorio->fecha->isToday() || $recordatorio->fecha->isFuture())
                                    <div class="mb-6 flex items-center">
                                        <input type="checkbox"
                                            name="realizado"
                                            id="realizado"
                                            value="1" {{ $recordatorio->realizado ? 'checked' : '' }}
                                            class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded mr-2">
                                        <label for="realizado" class="text-sm font-medium text-slate-700">
                                            Marcar como Hecho
                                        </label>
                                    </div>
                                @endif

                            <div class="flex justify-end space-x-3">
                                <a href="{{ url()->previous() }}"
                                   class="inline-block px-6 py-3 font-bold text-center bg-gray-500 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Volver
                                </a>
                                <button type="submit" 
                                        class="inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-purple-700 to-pink-500 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                    <i class="fas fa-save mr-2"></i>
                                    Guardar Cambios
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
                        <h6 class="mb-0">Información del Recordatorio</h6>
                    </div>
                    
                    <div class="flex-auto p-4">
                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar text-white text-sm"></i>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-slate-700">Fecha original</h6>
                                    <p class="text-sm text-slate-600">{{ $recordatorio->fecha->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-bell text-white text-sm"></i>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-slate-700">Estado</h6>
                                    <p class="text-sm text-slate-600">{{ $recordatorio->visto ? 'Visto' : 'Pendiente' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-clock text-white text-sm"></i>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-slate-700">Creado</h6>
                                    <p class="text-sm text-slate-600">{{ $recordatorio->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg p-4 text-white">
                            <h6 class="text-sm font-semibold mb-2">Nota</h6>
                            <p class="text-sm opacity-90">Los cambios se guardarán inmediatamente en el recordatorio.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
