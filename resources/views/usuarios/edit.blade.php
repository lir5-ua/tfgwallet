@extends('layouts.gestion')

@section('title', 'Editar Usuario - PetWallet')

@section('content')
<div class="bg-white text-black dark:bg-slate-800 dark:text-white p-4 rounded ease-soft-in-out relative h-full max-h-screen bg-gray-50 transition-all duration-200">
    <div class="w-full px-6 mx-auto dark:text-white">
        <!-- Header con imagen de fondo -->
        <div class="relative flex items-center p-0 mt-6 overflow-hidden bg-center bg-cover min-h-75 rounded-2xl"
             style="background-image: url('/assets/img/curved-images/curved0.jpg'); background-position-y: 50%">
            <span class="absolute inset-y-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-purple-700 to-pink-500 opacity-60"></span>
        </div>
        
        <!-- Tarjeta de perfil -->
        <div class="relative flex flex-col flex-auto min-w-0 p-4 mx-6 -mt-16 overflow-hidden break-words border-0 shadow-blur rounded-2xl bg-white/80 bg-clip-border backdrop-blur-2xl backdrop-saturate-200">
            <div class="flex flex-wrap -mx-3">
                <div class="flex-none w-auto max-w-full px-3">
                    <div class="text-base ease-soft-in-out h-18.5 w-18.5 relative inline-flex items-center justify-center rounded-xl text-white transition-all duration-200">
                        <img src="{{ $usuario->foto ? asset('storage/' . $usuario->foto) : asset('storage/default/defaultUser.jpg') }}"
                             alt="profile_image"
                             class="w-full shadow-soft-sm rounded-xl"/>
                    </div>
                </div>
                <div class="flex-none w-auto max-w-full px-3 my-auto">
                    <div class="h-full">
                        <h5 class="mb-1 text-slate-800 dark:text-white">Editar Perfil: {{ $usuario->name }}</h5>
                        <p class="mb-0 text-sm text-slate-600 dark:text-slate-300">Actualiza tu información personal</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="w-full p-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <!-- Formulario principal -->
            <div class="w-full max-w-full px-3 mx-auto lg:w-8/12">
                <div class="relative flex flex-col h-full min-w-0 break-words bg-white dark:bg-slate-700 border-0 shadow-soft-xl rounded-2xl bg-clip-border transition-all duration-300 hover:shadow-soft-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white dark:bg-slate-700 border-b-0 rounded-t-2xl">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-user-edit text-white text-lg"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-slate-800 dark:text-white font-semibold">Información Personal</h6>
                                <p class="text-sm text-slate-600 dark:text-slate-300">Completa los campos que desees actualizar</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex-auto p-6">
                        @if(session('error'))
                            <div id="alert-error" class="w-full p-4 mb-4 text-white rounded-lg bg-red-600 flex justify-between items-center">
                                <span>{{ session('error') }}</span>
                                <button onclick="document.getElementById('alert-error').remove()"
                                        class="ml-4 text-white hover:text-black font-bold text-lg leading-none">&times;
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('usuarios.update', $usuario) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            @method('PUT')
                            
                            <!-- Sección de foto de perfil -->
                            <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-slate-600 dark:to-slate-500 rounded-xl p-6">
                                <label for="foto" class="block text-sm font-semibold text-slate-700 dark:text-white mb-4">
                                    <i class="fas fa-camera mr-2 text-purple-600"></i>
                                    Foto de perfil
                                </label>
                                <div class="flex items-center space-x-6">
                                    <div class="relative">
                                        <img src="{{ $usuario->foto ? asset('storage/' . $usuario->foto) : asset('storage/default/defaultUser.jpg') }}"
                                             alt="Foto actual" 
                                             class="w-20 h-20 rounded-xl object-cover border-4 border-white shadow-soft-lg">
                                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-camera text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" 
                                               id="foto" 
                                               name="foto" 
                                               accept="image/*"
                                               class="block w-full text-sm text-slate-500 dark:text-slate-300 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-tl file:from-purple-700 file:to-pink-500 file:text-white hover:file:shadow-soft-lg transition-all duration-300 cursor-pointer">
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Formatos permitidos: JPG, PNG, GIF. Máximo 2MB.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Información básica -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="group">
                                    <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-white mb-3 group-hover:text-purple-600 transition-colors">
                                        <i class="fas fa-user mr-2 text-purple-600"></i>
                                        Nombre completo *
                                    </label>
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $usuario->name) }}"
                                           class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white dark:bg-slate-600 text-slate-700 dark:text-white placeholder-slate-400 dark:placeholder-slate-300 transition-all duration-300 hover:shadow-soft-sm"
                                           placeholder="Tu nombre completo"
                                           required>
                                </div>
                                
                                <div class="group">
                                    <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-white mb-3 group-hover:text-purple-600 transition-colors">
                                        <i class="fas fa-envelope mr-2 text-purple-600"></i>
                                        Email *
                                    </label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $usuario->email) }}"
                                           class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white dark:bg-slate-600 text-slate-700 dark:text-white placeholder-slate-400 dark:placeholder-slate-300 transition-all duration-300 hover:shadow-soft-sm"
                                           placeholder="tu@email.com"
                                           required>
                                </div>
                            </div>

                            <!-- Contraseñas -->
                            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-slate-600 dark:to-slate-500 rounded-xl p-6">
                                <h6 class="text-sm font-semibold text-slate-700 dark:text-white mb-4">
                                    <i class="fas fa-lock mr-2 text-blue-600"></i>
                                    Cambiar contraseña (opcional)
                                </h6>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="group">
                                        <label for="password" class="block text-sm font-medium text-slate-700 dark:text-white mb-3">
                                            Nueva contraseña
                                        </label>
                                        <div class="relative">
                                            <input type="password" 
                                                   id="password" 
                                                   name="password"
                                                   class="w-full px-4 py-3 pr-12 border border-slate-300 dark:border-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-slate-600 text-slate-700 dark:text-white placeholder-slate-400 dark:placeholder-slate-300 transition-all duration-300 hover:shadow-soft-sm"
                                                   placeholder="Mínimo 8 caracteres">
                                            <i class="fas fa-eye absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 cursor-pointer hover:text-slate-600" onclick="togglePassword('password')"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="group">
                                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-white mb-3">
                                            Confirmar contraseña
                                        </label>
                                        <div class="relative">
                                            <input type="password" 
                                                   id="password_confirmation" 
                                                   name="password_confirmation"
                                                   class="w-full px-4 py-3 pr-12 border border-slate-300 dark:border-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-slate-600 text-slate-700 dark:text-white placeholder-slate-400 dark:placeholder-slate-300 transition-all duration-300 hover:shadow-soft-sm"
                                                   placeholder="Repite la contraseña">
                                            <i class="fas fa-eye absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 cursor-pointer hover:text-slate-600" onclick="togglePassword('password_confirmation')"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-3">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Deja los campos vacíos si no quieres cambiar la contraseña actual.
                                </p>
                            </div>

                            <!-- Checkbox de administrador -->
                            @if(auth()->user()->is_admin)
                            <div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-slate-600 dark:to-slate-500 rounded-xl p-6">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="esAdmin" 
                                           name="esAdmin" 
                                           {{ $usuario->is_admin ? 'checked' : '' }}
                                           class="w-5 h-5 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 focus:ring-2 transition-all duration-300">
                                    <label for="esAdmin" class="ml-3 text-sm font-semibold text-slate-700 dark:text-white cursor-pointer">
                                        <i class="fas fa-crown mr-2 text-amber-600"></i>
                                        Otorgar permisos de administrador
                                    </label>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 ml-8">
                                    Los administradores tienen acceso completo al sistema.
                                </p>
                            </div>
                            @endif

                            <!-- Checkbox notificar por email -->
                            <div class="flex items-center mt-4">
                                <input type="checkbox"
                                       id="notificar_email"
                                       name="notificar_email"
                                       {{ old('notificar_email', $usuario->notificar_email) ? 'checked' : '' }}
                                       class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 transition-all duration-300">
                                <label for="notificar_email" class="ml-3 text-sm font-semibold text-slate-700 dark:text-white cursor-pointer">
                                    <i class="fas fa-envelope mr-2 text-blue-600"></i>
                                    Notificarme por email
                                </label>
                            </div>

                            <!-- Botones de acción -->
                            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-slate-200 dark:border-slate-600">
                                <a href="{{ url()->previous() }}" 
                                   class="inline-flex items-center justify-center px-6 py-3 font-bold text-center bg-slate-500 hover:bg-slate-600 uppercase align-middle transition-all rounded-xl cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Cancelar
                                </a>
                                <button type="submit" 
                                        class="inline-flex items-center justify-center px-6 py-3 font-bold text-center bg-gradient-to-tl from-purple-700 to-pink-500 hover:from-purple-800 hover:to-pink-600 uppercase align-middle transition-all rounded-xl cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                    <i class="fas fa-save mr-2"></i>
                                    Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Panel lateral con información -->
            <div class="w-full max-w-full px-3 mt-6 lg:mt-0 lg:w-4/12 lg:flex-none">
                <div class="relative flex flex-col h-full min-w-0 break-words bg-white dark:bg-slate-700 border-0 shadow-soft-xl rounded-2xl bg-clip-border transition-all duration-300 hover:shadow-soft-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white dark:bg-slate-700 border-b-0 rounded-t-2xl">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-info-circle text-white text-lg"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-slate-800 dark:text-white font-semibold">Información del Usuario</h6>
                                <p class="text-sm text-slate-600 dark:text-slate-300">Detalles de la cuenta</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex-auto p-6 space-y-6">
                        <!-- Fecha de registro -->
                        <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-slate-600 dark:to-slate-500 rounded-xl">
                            <div class="w-12 h-12 bg-gradient-to-tl from-green-600 to-emerald-400 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-calendar-plus text-white text-lg"></i>
                            </div>
                            <div>
                                <h6 class="text-sm font-semibold text-slate-700 dark:text-white">Fecha de registro</h6>
                                <p class="text-sm text-slate-600 dark:text-slate-300">{{ $usuario->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <!-- Última conexión -->
                        <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-slate-600 dark:to-slate-500 rounded-xl">
                            <div class="w-12 h-12 bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-clock text-white text-lg"></i>
                            </div>
                            <div>
                                <h6 class="text-sm font-semibold text-slate-700 dark:text-white">Última conexión</h6>
                                <p class="text-sm text-slate-600 dark:text-slate-300">
                                    {{ $usuario->ultima_conexion ? $usuario->ultima_conexion->format('d/m/Y H:i') : 'Nunca' }}
                                </p>
                            </div>
                        </div>

                        <!-- Rol del usuario -->
                        <div class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-slate-600 dark:to-slate-500 rounded-xl">
                            <div class="w-12 h-12 bg-gradient-to-tl from-purple-600 to-pink-400 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-user-shield text-white text-lg"></i>
                            </div>
                            <div>
                                <h6 class="text-sm font-semibold text-slate-700 dark:text-white">Rol actual</h6>
                                <p class="text-sm text-slate-600 dark:text-slate-300">
                                    {{ $usuario->is_admin ? 'Administrador' : 'Usuario' }}
                                </p>
                            </div>
                        </div>

                        <!-- Nota informativa -->
                        <div class="bg-gradient-to-tl from-purple-700 to-pink-500 rounded-xl p-4 text-white">
                            <div class="flex items-start">
                                <i class="fas fa-lightbulb text-yellow-300 text-lg mr-3 mt-1"></i>
                                <div>
                                    <h6 class="text-sm font-semibold mb-2">Consejo</h6>
                                    <p class="text-sm opacity-90">Mantén tu información actualizada para una mejor experiencia en la aplicación.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Estadísticas rápidas -->
                        <div class="bg-gradient-to-r from-slate-50 to-gray-50 dark:from-slate-600 dark:to-slate-500 rounded-xl p-4">
                            <h6 class="text-sm font-semibold text-slate-700 dark:text-white mb-3">Estadísticas</h6>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-purple-600">{{ $usuario->mascotas ? $usuario->mascotas->count() : 0 }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">Mascotas</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-pink-600">{{ $usuario->recordatorios ? $usuario->recordatorios->count() : 0 }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">Recordatorios</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = field.nextElementSibling;
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Animaciones suaves para los campos
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
    
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('scale-105');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('scale-105');
        });
    });
});
</script>
@endsection
