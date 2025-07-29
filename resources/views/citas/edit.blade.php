@extends('layouts.app')

@section('title', 'Editar Cita')

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
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-none w-auto max-w-full px-3 my-auto">
                    <div class="h-full">
                        <h5 class="mb-1">Editar Cita</h5>
                        <p class="mb-0 text-sm text-slate-600">Modifica los detalles de la cita veterinaria</p>
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
                        <h6 class="mb-0">Información de la Cita</h6>
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

                        <!-- Información del cliente -->
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                            <h6 class="text-sm font-semibold text-blue-700 mb-2">Cliente</h6>
                            <p class="text-sm text-blue-600">
                                <strong>Usuario:</strong> {{ $usuario->name }} ({{ $usuario->email }})<br>
                                <strong>Mascota:</strong> {{ $mascota->nombre }} ({{ $mascota->especie }})
                            </p>
                        </div>

                        <form action="{{ route('citas.update', $cita->hashid) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-6">
                                <h6 class="text-sm font-semibold text-slate-700 mb-4">Detalles de la Cita</h6>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="titulo" class="block text-sm font-medium text-slate-700 mb-2">
                                            Título de la cita *
                                        </label>
                                        <input type="text" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white" 
                                               id="titulo" 
                                               name="titulo" 
                                               value="{{ old('titulo', $cita->titulo) }}" 
                                               placeholder="Ej: Revisión anual, Vacunación, etc."
                                               required>
                                    </div>
                                    <div>
                                        <label for="fecha" class="block text-sm font-medium text-slate-700 mb-2">
                                            Fecha y hora de la cita *
                                        </label>
                                        <input type="datetime-local" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white" 
                                               id="fecha" 
                                               name="fecha" 
                                               value="{{ old('fecha', $cita->fecha->format('Y-m-d\TH:i')) }}" 
                                               min="{{ now()->format('Y-m-d\TH:i') }}"
                                               required>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label for="descripcion" class="block text-sm font-medium text-slate-700 mb-2">
                                        Descripción
                                    </label>
                                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white" 
                                              id="descripcion" 
                                              name="descripcion" 
                                              rows="3" 
                                              placeholder="Detalles adicionales de la cita...">{{ old('descripcion', $cita->descripcion) }}</textarea>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('veterinarios.perfil', auth()->guard('veterinarios')->user()->hashid) }}"
                                   class="inline-block px-6 py-3 font-bold text-center bg-gray-500 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Cancelar
                                </a>
                                <button type="submit" 
                                        class="inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-purple-700 to-pink-500 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                    <i class="fas fa-save mr-2"></i>
                                    Actualizar Cita
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
                        <h6 class="mb-0">Información de la Cita</h6>
                    </div>
                    
                    <div class="flex-auto p-4">
                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar text-white text-sm"></i>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-slate-700">Fecha Original</h6>
                                    <p class="text-sm text-slate-600">{{ $cita->fecha->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-slate-700">Estado</h6>
                                    <p class="text-sm text-slate-600">
                                        @if($cita->realizado)
                                            <span class="text-green-600 font-semibold">Realizada</span>
                                        @else
                                            <span class="text-yellow-600 font-semibold">Pendiente</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg p-4 text-white">
                            <h6 class="text-sm font-semibold mb-2">Consejo</h6>
                            <p class="text-sm opacity-90">Recuerda notificar al cliente sobre cualquier cambio en la cita.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 