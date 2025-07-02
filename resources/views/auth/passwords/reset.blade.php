@extends('layouts.app')

@section('title', 'Restablecer contraseña')

@section('content')
<div class="flex justify-center items-center min-h-[60vh]">
    <div class="w-full max-w-md bg-white dark:bg-slate-400 dark:text-white rounded-2xl shadow-soft-xl p-8 border border-orange-200">
        <h2 class="text-2xl font-bold text-center text-orange-500 mb-2">Restablecer contraseña</h2>
        <p class="text-center text-slate-600 dark:text-slate-200 mb-6">Introduce tu nueva contraseña para tu cuenta.</p>
        @if ($errors->any())
            <div class="mb-4 font-medium text-sm text-red-600 text-center">
                {{ $errors->first() }}
            </div>
        @endif
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">
            <label for="password" class="block text-sm font-bold text-slate-700 dark:text-white">Nueva contraseña</label>
            <input type="password" name="password" id="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent dark:bg-slate-800 dark:border-slate-600 dark:text-white" placeholder="Nueva contraseña">
            <label for="password_confirmation" class="block text-sm font-bold text-slate-700 dark:text-white">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent dark:bg-slate-800 dark:border-slate-600 dark:text-white" placeholder="Confirma la contraseña">
            <button type="submit" class="w-full px-6 py-2 font-bold text-white uppercase rounded-lg bg-gradient-to-tl from-orange-500 to-yellow-400 shadow-md hover:scale-102 transition-all">Restablecer contraseña</button>
        </form>
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">Volver al inicio de sesión</a>
        </div>
    </div>
</div>
@endsection 