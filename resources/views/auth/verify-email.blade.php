@extends('layouts.app')

@section('title', 'Verifica tu correo')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 bg-white rounded shadow">
        <h1 class="text-2xl font-bold mb-4 text-center">Verifica tu correo electrónico</h1>
        <p class="mb-4 text-center">Te hemos enviado un enlace de verificación a tu correo electrónico. Por favor, revisa tu bandeja de entrada y haz clic en el enlace para activar tu cuenta.</p>
        @if (session('message'))
            <div class="mb-4 text-green-600 text-center">{{ session('message') }}</div>
        @endif
        <form method="POST" action="{{ route('verification.send') }}" class="text-center">
            @csrf
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Reenviar enlace de verificación</button>
        </form>
        <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
            @csrf
            <button type="submit" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Cerrar sesión</button>
        </form>
    </div>
</div>
@endsection 