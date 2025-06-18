<!DOCTYPE html>
<html lang="en">
@extends('layouts.app')

@section('title','mascotas')


@section('content')
@if (isset($recordatorios) && $recordatorios->isNotEmpty())
@foreach (['hoy' => $hoy, 'mañana' => $manana, 'pasado' => $pasado] as $nombre => $fecha)
@php
$items = $recordatorios->filter(function ($rec) use ($fecha) {
    return \Carbon\Carbon::parse($rec->fecha)->toDateString() === $fecha;
});
@endphp

@if ($items->isNotEmpty())
<h2 class="mt-6 text-xl font-bold">
    Recordatorios de {{ $nombre }}
</h2>

@foreach ($items as $recordatorio)
<div class="p-4 my-2 rounded-lg
                    {{ $nombre === 'hoy' ? 'bg-lime-200 text-lime-600' : ($nombre === 'mañana' ? 'bg-yellow-100' : 'bg-green-100') }}">
    <strong>{{ $recordatorio->titulo }}</strong><br>
    {{ $recordatorio->descripcion }}

    <form action="{{ route('recordatorios.visto', $recordatorio) }}" method="POST" class="inline">
        @csrf
        @method('PATCH')
        <button type="submit"
                class="mt-2 inline-block px-4 py-1.5 font-bold text-center bg-gradient-to-tl from-purple-700 to-pink-500 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
            👁️ Hecho
        </button>
    </form>
</div>
@endforeach
@endif
@endforeach
@endif

<div class="container">
    <h1 class="text-2xl font-bold">{{ $titulo }}</h1>

    @if(session('success'))
    <div id="alert-success"
         class="relative w-full p-4 text-white rounded-lg bg-lime-500 flex justify-between items-center">
        <span>{{ session('success') }}</span>
        <button onclick="document.getElementById('alert-success').remove()"
                class="ml-4 text-white hover:text-black font-bold text-lg leading-none">&times;
        </button>
    </div>
    @endif

    <div class="flex flex-wrap items-center space-x-4 mb-4">


        <!-- Formulario de búsqueda -->
        <form method="GET" action="{{ route('mascotas.index') }}" class="flex items-center space-x-2">
            <!-- Input de búsqueda con icono -->
            <!-- Botón Crear -->
            <a href="{{ route('mascotas.create') }}"
               class="h-10 px-6 py-2 font-bold text-center bg-gradient-to-tl from-green-600 to-lime-400 uppercase rounded-lg text-xs text-white flex items-center justify-center">
                Crear
            </a>
            <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-2 flex items-center text-slate-500">
                <i class="fas fa-search"></i>
            </span>
                <input type="text"
                       name="busqueda"
                       value="{{ request('busqueda') }}"
                       placeholder="Buscar por nombre"
                       class="pl-9 pr-3 py-2 text-sm w-64 rounded-lg border border-gray-300 text-gray-700 placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none"/>
            </div>

            <!-- Botón Buscar -->
            <button type="submit"
                    class="h-10 px-6 py-2 font-bold bg-gradient-to-tl from-red-500 to-yellow-400 uppercase rounded-lg text-xs text-white flex items-center justify-center">
                Buscar
            </button>



            <!-- Botón Resetear -->
            <a href="{{ route('mascotas.index') }}"
               class="h-10 px-6 py-2 font-bold bg-gradient-to-tl from-slate-600 to-slate-300 uppercase rounded-lg text-xs text-white flex items-center justify-center">
                Resetear
            </a>
        </form>
    </div>
    <div
        class="relative flex flex-col w-full min-w-0 mb-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
        <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
            <h6 class="text-lg font-semibold">Mascotas registradas</h6>
        </div>
        <div
            class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6 class="text-lg font-semibold text-slate-700">Mascotas</h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom">
                        <tr>
                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 text-xxs tracking-wide text-slate-400 opacity-70">
                                Mascota
                            </th>
                            <th class="px-6 py-3 text-center font-bold uppercase align-middle bg-transparent border-b border-gray-200 text-xxs tracking-wide text-slate-400 opacity-70">
                                Especie
                            </th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 text-xxs tracking-wide text-slate-400 opacity-70">
                                Raza
                            </th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 text-xxs tracking-wide text-slate-400 opacity-70">
                                Sexo
                            </th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 text-xxs tracking-wide text-slate-400 opacity-70">
                                Acciones
                            </th>
                        </tr>
                        <tr>
                            <form method="GET" action="{{ route('mascotas.index') }}" class="flex items-center space-x-2">
                            <!-- Filtro por Especie -->
                                <th></th>
                                <th>
                            <select name="especie" class="py-2 px-3 text-sm rounded-lg border border-gray-300 text-gray-700 focus:border-fuchsia-300 focus:outline-none">
                                <option value="">-- Especie --</option>
                                @foreach($especies as $especie)
                                <option value="{{ $especie['value'] }}" {{ request('especie') == $especie['value'] ? 'selected' : '' }}>
                                {{ $especie['label'] }}
                                </option>
                                @endforeach
                            </select>
                                </th>
<th>
                            <!-- Filtro por Raza -->
                            <select name="raza" class="py-2 px-3 text-sm rounded-lg border border-gray-300 text-gray-700 focus:border-fuchsia-300 focus:outline-none">
                                <option value="">-- Raza --</option>
                                @php
                                $todasRazas = collect($razasPorEspecie)->flatten()->unique();
                                @endphp
                                @foreach($todasRazas as $raza)
                                <option value="{{ $raza }}" {{ request('raza') == $raza ? 'selected' : '' }}>
                                {{ $raza }}
                                </option>
                                @endforeach
                            </select>
</th>
<th>
                            <!-- Filtro por Sexo -->
                            <select name="sexo" class="py-2 px-3 text-sm rounded-lg border border-gray-300 text-gray-700 focus:border-fuchsia-300 focus:outline-none">
                                <option value="">-- Sexo --</option>
                                @foreach($sexos as $sexo)
                                <option value="{{ $sexo->value }}" {{ request('sexo') == $sexo->value ? 'selected' : '' }}>
                                {{ ucfirst($sexo->value) }}
                                </option>
                                @endforeach
                            </select>
</th>
                                <th class="px-6 py-2 text-center">
                                    <button type="submit"
                                            class="px-4 py-2 text-xs font-bold text-white bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg uppercase">
                                        Filtrar
                                    </button>
                                </th>
                            </form>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($mascotas as $mascota)
                        <tr>
                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                <div class="flex px-2 py-1">
                                    <div>
                                        <img
                                            src="{{ asset('storage/' . $mascota->imagen) }}"
                                            class="inline-flex items-center justify-center mr-4 text-sm text-white h-9 w-9 max-w-none rounded-xl"
                                            alt="{{ $mascota->nombre }}">
                                    </div>
                                    <div class="flex flex-col justify-center">
                                        <h6 class="mb-0 max-w-[200px] text-sm leading-normal">
                                            <a href="{{ route('mascotas.show', $mascota) }}"
                                               class="text-sm block truncate font-semibold text-slate-700 hover:text-blue-500">
                                                {{ $mascota->nombre }}

                                            </a>
                                        </h6>
                                        <p class="mb-0 text-xs leading-tight text-slate-400">{{ $mascota->usuario->name
                                            ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-sm  text-center text-slate-500 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                <span
                                    class="text-xs  font-medium text-slate-600">{{ ucfirst($mascota->especie->value) }}</span>
                            </td>
                            <td class="p-4 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                <span class="text-xs text-slate-600">{{ $mascota->raza }}</span>
                            </td>
                            <td class="text-center">
                                <span class="inline-block px-3 py-1 mr-2 font-semibold text-center text-white uppercase rounded-md text-xs shadow-sm hover:scale-105 transition
                                    {{ $mascota->sexo?->value === 'M' ? 'bg-blue-500' : 'bg-fuchsia-500' }}">
                                    {{ $mascota->sexo?->value }}
                                </span>
                            </td>
                            <td class="p-4 align-middle text-center bg-transparent border-b whitespace-nowrap shadow-transparent">
                                <a href="{{ route('mascotas.edit', $mascota) }}"
                                   class="mr-3 inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-blue-600 to-cyan-400 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">✏️
                                    Editar</a>
                                <form action="{{ route('mascotas.destroy', $mascota) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('¿Seguro que quieres eliminar esta mascota?')"
                                            class="mr-3 inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-red-600 to-rose-400 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                        🗑️ Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
</div>
</div>

@if ($mascotas->hasPages())
<div style="margin-top: 20px; display: flex; justify-content: center; flex-direction: column; align-items: center;">
    <x-pagination-info :paginator="$mascotas" itemName="mascotas" />
    {{ $mascotas->links() }}
</div>
@endif
@endsection
</html>
