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
                        <h5 class="mb-1">Editar Entrada Médica</h5>
                        <p class="mb-0 text-sm text-slate-600">Actualiza la información de {{ $mascota->nombre }}</p>
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
                        <h6 class="mb-0">Información Médica</h6>
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

                        <form action="{{ route('mascotas.historial.update', [$mascota, $historial]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 md:w-1/2 md:flex-none">
                                    <div class="mb-4">
                                        <label for="fecha" class="block text-sm font-medium text-slate-700 mb-2">
                                            Fecha de la consulta *
                                        </label>
                                        <input type="date" 
                                               id="fecha" 
                                               name="fecha" 
                                               value="{{ old('fecha', $historial->fecha->format('Y-m-d')) }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                               required>
                                    </div>
                                </div>
                                
                                <div class="w-full max-w-full px-3 md:w-1/2 md:flex-none">
                                    <div class="mb-4">
                                        <label for="tipo" class="block text-sm font-medium text-slate-700 mb-2">
                                            Tipo de consulta *
                                        </label>
                                        <select id="tipo" 
                                                name="tipo"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white">
                                            @foreach (App\Enums\TipoHistorial::cases() as $tipo)
                                                <option value="{{ $tipo->value }}" {{ old('tipo', $historial->tipo) == $tipo->value ? 'selected' : '' }}>
                                                    {{ ucfirst($tipo->value) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="veterinario" class="block text-sm font-medium text-slate-700 mb-2">
                                    Veterinario
                                </label>
                                <input type="text" 
                                       id="veterinario" 
                                       name="veterinario" 
                                       value="{{ old('veterinario', $historial->veterinario) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                       placeholder="Nombre del veterinario">
                            </div>

                            <div class="mb-6">
                                <label for="descripcion" class="block text-sm font-medium text-slate-700 mb-2">
                                    Descripción de la consulta *
                                </label>
                                <textarea id="descripcion" 
                                          name="descripcion" 
                                          rows="6"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                          placeholder="Describe los síntomas, diagnóstico, tratamiento, etc..."
                                          required>{{ old('descripcion', $historial->descripcion) }}</textarea>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <a href="{{ session('return_to_after_update', route('usuarios.show', auth()->user())) }}"
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
                                    <h6 class="text-sm font-semibold text-slate-700">Fecha original</h6>
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
                            <p class="text-sm opacity-90">Los cambios se guardarán inmediatamente en el historial médico.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
