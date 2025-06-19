@extends('layouts.app')

@section('title', 'Sobre nosotros')
@section('content')
<div class="w-full px-6 mx-auto dark:text-white">
    <!-- Breadcrumb -->
    <nav class="flex items-center text-sm mb-4 mt-6 mx-auto max-w-2xl" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="/" class="inline-flex items-center text-slate-600 hover:text-purple-700 dark:text-slate-200 dark:hover:text-pink-400 font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m4-8v8m5 0a2 2 0 002-2V7a2 2 0 00-.59-1.41l-7-7a2 2 0 00-2.82 0l-7 7A2 2 0 003 7v11a2 2 0 002 2h3"></path></svg>
                    Inicio
                </a>
            </li>
            <li>
                <span class="mx-2 text-slate-400 dark:text-slate-400">/</span>
            </li>
            <li class="inline-flex items-center text-slate-800 dark:text-white font-semibold">
                Sobre nosotros
            </li>
        </ol>
    </nav>
    <!-- /Breadcrumb -->
    <div class="relative flex items-center p-0 mt-6 overflow-hidden bg-center bg-cover min-h-75 rounded-2xl"
         style="background-image: url('/assets/img/curved-images/curved0.jpg'); background-position-y: 50%">
        <span class="absolute inset-y-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-purple-700 to-pink-500 opacity-60"></span>
    </div>
    <div class="relative flex flex-col flex-auto min-w-0 p-4 mx-6 -mt-16 overflow-hidden break-words border-0 shadow-blur rounded-2xl bg-white/80 dark:bg-slate-700/80 bg-clip-border backdrop-blur-2xl backdrop-saturate-200 max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-4 text-slate-800 dark:text-white">Sobre PetWallet</h1>
        <p class="mb-4 text-lg text-slate-600 dark:text-slate-200">PetWallet es una plataforma digital diseñada para ayudarte a gestionar el historial médico y la información relevante de tus mascotas de forma sencilla, segura y accesible desde cualquier dispositivo. Nuestro objetivo es facilitar el cuidado animal y la organización de datos veterinarios para dueños responsables.</p>
        <h2 class="text-2xl font-semibold mt-8 mb-2 text-slate-700 dark:text-slate-100">Sobre el creador</h2>
        <p class="text-slate-600 dark:text-slate-200">Esta web ha sido desarrollada por <strong class="text-purple-700 dark:text-pink-400">Luis Ivorra</strong> como parte de su Trabajo de Fin de Grado. Luis es un apasionado de la tecnología y los animales, y ha creado PetWallet para aportar valor a la comunidad de dueños de mascotas, combinando sus conocimientos en desarrollo web y su amor por los animales.</p>
        <div class="mt-8 text-center">
            <a href="#" onclick="window.history.back(); return false;" class="inline-block px-6 py-2 font-bold text-white bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg shadow-md hover:scale-105 transition-transform">Volver</a>
        </div>
    </div>
</div>
@endsection 