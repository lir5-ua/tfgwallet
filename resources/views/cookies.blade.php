@extends('layouts.app')

@section('title', 'Política de Cookies')
@section('content')
<div class="w-full px-6 mx-auto dark:text-white">
    <div class="relative flex items-center p-0 mt-6 overflow-hidden bg-center bg-cover min-h-75 rounded-2xl"
         style="background-image: url('/assets/img/curved-images/curved0.jpg'); background-position-y: 50%">
        <span class="absolute inset-y-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-purple-700 to-pink-500 opacity-60"></span>
    </div>
    <div class="relative flex flex-col flex-auto min-w-0 p-4 mx-6 -mt-16 overflow-hidden break-words border-0 shadow-blur rounded-2xl bg-white/80 bg-clip-border backdrop-blur-2xl backdrop-saturate-200 max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-4 text-slate-800 dark:text-white">Política de Cookies</h1>
        <p class="mb-4 text-lg text-slate-600 dark:text-slate-200">En PetWallet utilizamos cookies propias y de terceros para mejorar tu experiencia de usuario, analizar el tráfico y personalizar el contenido. Al navegar por nuestra web, aceptas el uso de cookies.</p>
        <ul class="list-disc pl-6 mb-4 text-slate-600 dark:text-slate-200">
            <li>Las cookies son pequeños archivos que se almacenan en tu dispositivo para recordar tus preferencias o sesión.</li>
            <li>No utilizamos cookies para recopilar información personal sensible.</li>
            <li>Puedes eliminar o bloquear las cookies desde la configuración de tu navegador.</li>
        </ul>
        <p class="text-slate-600 dark:text-slate-200">Si tienes dudas sobre nuestra política de cookies, puedes contactarnos a través de la sección de <a href="/soporte/contacto" class="text-blue-600 underline">soporte</a>.</p>
        <div class="mt-8 text-center">
            <a href="#" onclick="window.history.back(); return false;" class="inline-block px-6 py-2 font-bold text-white bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg shadow-md hover:scale-105 transition-transform">Volver</a>
        </div>
    </div>
</div>
@endsection 