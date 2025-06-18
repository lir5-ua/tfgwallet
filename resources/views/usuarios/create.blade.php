@extends('layouts.gestion')

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
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-none w-auto max-w-full px-3 my-auto">
                    <div class="h-full">
                        <h5 class="mb-1">Crear Nuevo Usuario</h5>
                        <p class="mb-0 text-sm text-slate-600">Registra un nuevo usuario en el sistema</p>
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
                        <h6 class="mb-0">Información del Usuario</h6>
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

                        <form action="{{ route('usuarios.store') }}" method="POST">
                            @csrf
                            
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 md:w-1/2 md:flex-none">
                                    <div class="mb-4">
                                        <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                                            Nombre completo *
                                        </label>
                                        <input type="text" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                               placeholder="Nombre del usuario"
                                               required>
                                    </div>
                                </div>
                                
                                <div class="w-full max-w-full px-3 md:w-1/2 md:flex-none">
                                    <div class="mb-4">
                                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                                            Email *
                                        </label>
                                        <input type="email" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                               placeholder="usuario@email.com"
                                               required>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 md:w-1/2 md:flex-none">
                                    <div class="mb-4">
                                        <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                                            Contraseña *
                                        </label>
                                        <input type="password" 
                                               id="password" 
                                               name="password"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                               placeholder="Contraseña segura"
                                               required>
                                    </div>
                                </div>
                                
                                <div class="w-full max-w-full px-3 md:w-1/2 md:flex-none">
                                    <div class="mb-6">
                                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">
                                            Confirmar contraseña *
                                        </label>
                                        <input type="password" 
                                               id="password_confirmation" 
                                               name="password_confirmation"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                               placeholder="Repite la contraseña"
                                               required>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('usuarios.index') }}" 
                                   class="inline-block px-6 py-3 font-bold text-center bg-gray-500 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Volver
                                </a>
                                <button type="submit" 
                                        class="inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-purple-700 to-pink-500 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                    <i class="fas fa-save mr-2"></i>
                                    Crear Usuario
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
                        <h6 class="mb-0">Información Importante</h6>
                    </div>
                    
                    <div class="flex-auto p-4">
                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-shield-alt text-white text-sm"></i>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-slate-700">Seguridad</h6>
                                    <p class="text-sm text-slate-600">La contraseña debe ser segura y única</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-envelope text-white text-sm"></i>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-slate-700">Email Único</h6>
                                    <p class="text-sm text-slate-600">Cada usuario debe tener un email único</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg p-4 text-white">
                            <h6 class="text-sm font-semibold mb-2">Permisos</h6>
                            <p class="text-sm opacity-90">Los usuarios nuevos tendrán permisos básicos por defecto.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
