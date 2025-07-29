@extends('layouts.app')

@section('title', 'Mascotas del Cliente')

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
                            <path fill-rule="evenodd" d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-none w-auto max-w-full px-3 my-auto">
                    <div class="h-full">
                        <h5 class="mb-1">Mascotas de {{ $usuario->name }}</h5>
                        <p class="mb-0 text-sm text-slate-600">Historial de citas por mascota</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="w-full p-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto">
                <div class="relative flex flex-col h-full min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h6 class="text-lg font-semibold text-slate-700 dark:text-white">Cliente: {{ $usuario->name }}</h6>
                                <p class="text-sm text-slate-600 dark:text-white">{{ $usuario->email }}</p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('veterinarios.usuarios', $veterinario->hashid) }}"
                                   class="inline-block px-4 py-2 font-bold text-center bg-gray-500 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Volver
                                </a>
                                <a href="{{ route('citas.create') }}"
                                   class="inline-block px-4 py-2 font-bold text-center bg-gradient-to-tl from-purple-700 to-pink-500 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                    <i class="fas fa-plus mr-2"></i>
                                    Nueva Cita
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex-auto p-4">
                        @if($mascotas->count() > 0)
                            <div class="space-y-6">
                                @foreach($mascotas as $mascota)
                                    <div class="relative flex flex-col h-full min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                                        <div class="p-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <div class="w-12 h-12 bg-gradient-to-tl from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center mr-4">
                                                        <i class="fas fa-paw text-white"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="text-lg font-semibold text-slate-700 dark:text-white">{{ $mascota->nombre }}</h6>
                                                        <p class="text-sm text-slate-600 dark:text-white">{{ $mascota->especie }} • {{ $mascota->sexo }}</p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-sm font-semibold text-slate-700 dark:text-white">
                                                        {{ $mascota->recordatorios->count() }} citas
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex-auto p-4">
                                            @if($mascota->recordatorios->count() > 0)
                                                <div class="space-y-3">
                                                    @foreach($mascota->recordatorios as $cita)
                                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                            <div class="flex items-center">
                                                                <div class="w-8 h-8 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                                                    <i class="fas fa-calendar text-white text-xs"></i>
                                                                </div>
                                                                <div>
                                                                    <p class="text-sm font-medium text-slate-700 dark:text-white">{{ $cita->titulo }}</p>
                                                                    <p class="text-xs text-slate-600 dark:text-white">
                                                                        {{ $cita->fecha->format('d/m/Y H:i') }}
                                                                        @if($cita->descripcion)
                                                                            • {{ Str::limit($cita->descripcion, 50) }}
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="flex items-center space-x-2">
                                                                @if($cita->realizado)
                                                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                                                        Realizada
                                                                    </span>
                                                                @else
                                                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">
                                                                        Pendiente
                                                                    </span>
                                                                @endif
                                                                <a href="{{ route('citas.edit', $cita->hashid) }}"
                                                                   class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full hover:bg-blue-200 transition-colors">
                                                                    Editar
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-center py-6">
                                                    <p class="text-sm text-slate-600 dark:text-white">No hay citas registradas para esta mascota.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-24 h-24 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-paw text-white text-2xl"></i>
                                </div>
                                <h6 class="text-lg font-semibold text-slate-700 dark:text-white mb-2">No hay mascotas con citas</h6>
                                <p class="text-sm text-slate-600 dark:text-white mb-6">
                                    Este cliente no tiene mascotas con citas registradas.
                                </p>
                                <a href="{{ route('citas.create') }}"
                                   class="inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-purple-700 to-pink-500 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                    <i class="fas fa-plus mr-2"></i>
                                    Crear Primera Cita
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 