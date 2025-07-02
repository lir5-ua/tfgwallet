@extends('layouts.app')

@section('title', 'Recuperar contraseña')

@section('content')
<div class="flex justify-center items-center min-h-[60vh]">
    <div class="w-full max-w-md bg-white dark:bg-slate-400 dark:text-white rounded-2xl shadow-soft-xl p-8 border border-orange-200">
        <h2 class="text-2xl font-bold text-center text-orange-500 mb-2">¿Olvidaste tu contraseña?</h2>
        <p class="text-center text-slate-600 dark:text-slate-200 mb-6">Introduce tu correo y te enviaremos un enlace para restablecerla.</p>
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 text-center">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 font-medium text-sm text-red-600 text-center">
                {{ $errors->first('email') }}
            </div>
        @endif
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <label for="email" class="block text-sm font-bold text-slate-700 dark:text-white">Correo electrónico</label>
            <input type="email" name="email" id="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent dark:bg-slate-800 dark:border-slate-600 dark:text-white" placeholder="Introduce tu correo electrónico">
            <button type="submit" class="w-full px-6 py-2 font-bold text-white uppercase rounded-lg bg-gradient-to-tl from-orange-500 to-yellow-400 shadow-md hover:scale-102 transition-all">Enviar enlace de recuperación</button>
        </form>
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">Volver al inicio de sesión</a>
        </div>
    </div>
</div>
@endsection 