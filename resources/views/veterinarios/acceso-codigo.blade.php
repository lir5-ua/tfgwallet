@extends('layouts.app')
@section('title','Acceso a Mascota por Código')
@section('content')
<div class="max-w-md mx-auto my-10 p-6 bg-white dark:bg-slate-700 rounded shadow">
    <h2 class="text-xl font-bold mb-4 text-center">Acceso a Mascota por Código</h2>
    <form method="POST" action="{{ route('acceso.historial') }}">
        @csrf
        <label for="codigo" class="block text-sm font-bold mb-2">Introduce el código proporcionado por el usuario:</label>
        <div class="flex">
            <input type="text" name="codigo" id="codigo" class="w-full px-3 py-2 border rounded-l" placeholder="Código de acceso..." required autofocus>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-r">Acceder</button>
        </div>
        @if(session('error_codigo'))
            <div class="text-red-600 mt-2">{{ session('error_codigo') }}</div>
        @endif
    </form>
</div>
@endsection 