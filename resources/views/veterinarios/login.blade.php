@extends('layouts.app')
@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100 dark:bg-slate-800">
    <div class="w-full max-w-md p-8 bg-white rounded shadow-md dark:bg-slate-700">
        <h2 class="text-2xl font-bold mb-6 text-center">Iniciar sesión como Veterinario</h2>
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('veterinarios.login.submit') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" required autofocus class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input type="password" name="password" id="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-gradient-to-tl from-purple-700 to-pink-500 text-white font-bold rounded-lg hover:scale-105 transition">Iniciar sesión</button>
        </form>
    </div>
</div>
@endsection 