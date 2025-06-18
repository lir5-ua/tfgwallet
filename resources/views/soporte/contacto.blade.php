@extends('layouts.app')

@section('title', 'Contactar con Soporte')

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
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-2 0c0 .993-.241 1.929-.668 2.754l-1.524-1.525a3.997 3.997 0 00.078-2.183l1.562-1.562C15.802 8.249 16 9.1 16 10zm-5.165 3.913l1.58 1.58A5.98 5.98 0 0110 16a5.976 5.976 0 01-2.516-.552l1.562-1.562a4.006 4.006 0 001.789.027zm-4.677-2.796a4.002 4.002 0 01-.041-2.08l-.08.08-1.53-1.533A5.98 5.98 0 004 10c0 .954.223 1.856.619 2.657l1.54-1.54zm1.088-6.45A5.974 5.974 0 0110 4c.954 0 1.856.223 2.657.619l-1.54 1.54a4.002 4.002 0 00-2.346.033L7.246 4.668zM12 10a2 2 0 11-4 0 2 2 0 014 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-none w-auto max-w-full px-3 my-auto">
                    <div class="h-full">
                        <h5 class="mb-1 dark:text-white">Contactar con Soporte</h5>
                        <p class="mb-0 text-sm text-slate-600 dark:text-white">¿Necesitas ayuda? Estamos aquí para asistirte</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario de contacto -->
    <div class="w-full p-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto lg:w-8/12">
                <div class="relative flex flex-col h-full min-w-0 break-words bg-white dark:bg-slate-400 dark:text-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-4 pb-0 mb-0 bg-white dark:bg-slate-400 dark:text-white border-b-0 rounded-t-2xl">
                        <h6 class="mb-0 dark:text-white">Formulario de Contacto</h6>
                    </div>
                    
                    <div class="flex-auto p-4">
                        @if(session('success'))
                            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('soporte.enviar') }}" method="POST">
                            @csrf
                            
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 md:w-1/2 md:flex-none">
                                    <div class="mb-4">
                                        <label for="nombre" class="block text-sm font-medium text-slate-700 dark:text-white mb-2">
                                            Nombre completo *
                                        </label>
                                        <input type="text" 
                                               id="nombre" 
                                               name="nombre" 
                                               value="{{ old('nombre') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-slate-600 dark:text-white dark:border-gray-500"
                                               placeholder="Tu nombre completo"
                                               required>
                                    </div>
                                </div>
                                
                                <div class="w-full max-w-full px-3 md:w-1/2 md:flex-none">
                                    <div class="mb-4">
                                        <label for="email" class="block text-sm font-medium text-slate-700 dark:text-white mb-2">
                                            Email *
                                        </label>
                                        <input type="email" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-slate-600 dark:text-white dark:border-gray-500"
                                               placeholder="tu@email.com"
                                               required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="asunto" class="block text-sm font-medium text-slate-700 dark:text-white mb-2">
                                    Asunto *
                                </label>
                                <input type="text" 
                                       id="asunto" 
                                       name="asunto" 
                                       value="{{ old('asunto') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-slate-600 dark:text-white dark:border-gray-500"
                                       placeholder="¿En qué podemos ayudarte?"
                                       required>
                            </div>

                            <div class="mb-6">
                                <label for="mensaje" class="block text-sm font-medium text-slate-700 dark:text-white mb-2">
                                    Mensaje *
                                </label>
                                <textarea id="mensaje" 
                                          name="mensaje" 
                                          rows="6"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-slate-600 dark:text-white dark:border-gray-500"
                                          placeholder="Describe tu problema o consulta en detalle..."
                                          required>{{ old('mensaje') }}</textarea>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-purple-700 to-pink-500 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Enviar Mensaje
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Información de contacto -->
            <div class="w-full max-w-full px-3 mt-6 lg:mt-0 lg:w-4/12 lg:flex-none">
                <div class="relative flex flex-col h-full min-w-0 break-words bg-white dark:bg-slate-400 dark:text-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-4 pb-0 mb-0 bg-white dark:bg-slate-400 dark:text-white border-b-0 rounded-t-2xl">
                        <h6 class="mb-0 dark:text-white">Información de Contacto</h6>
                    </div>
                    
                    <div class="flex-auto p-4">
                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-envelope text-white text-sm"></i>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-slate-700 dark:text-white">Email</h6>
                                    <p class="text-sm text-slate-600 dark:text-white">soporte@tfgwallet.com</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-clock text-white text-sm"></i>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-slate-700 dark:text-white">Horario de Atención</h6>
                                    <p class="text-sm text-slate-600 dark:text-white">Lunes a Viernes: 9:00 - 18:00</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-reply text-white text-sm"></i>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-slate-700 dark:text-white">Tiempo de Respuesta</h6>
                                    <p class="text-sm text-slate-600 dark:text-white">24-48 horas</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg p-4 text-white">
                            <h6 class="text-sm font-semibold mb-2">¿Necesitas ayuda urgente?</h6>
                            <p class="text-sm opacity-90">Para problemas críticos, puedes contactarnos directamente por email.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 