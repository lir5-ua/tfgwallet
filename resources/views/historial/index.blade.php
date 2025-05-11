
@extends('layouts.app')

@section('title', 'historialMedico')

@section('content')
<div class="flex flex-wrap items-center space-x-4 mb-4">


    <!-- Formulario de búsqueda -->
    <form method="GET" action="{{ route('mascotas.historial.index', ['mascota' => $mascota->id]) }}" class="flex items-center space-x-2">
        <!-- Input de búsqueda con icono -->
        <!-- Botón Crear -->
        <a href="{{ route('mascotas.historial.create', $mascota) }}"
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
        <a href="{{ route('mascotas.historial.index', $mascota) }}"
           class="h-10 px-6 py-2 font-bold bg-gradient-to-tl from-slate-600 to-slate-300 uppercase rounded-lg text-xs text-white flex items-center justify-center">
            Resetear
        </a>
    </form>
</div>

    @if ($historiales->isEmpty())
        <p>No hay entradas en el historial médico.</p>
    @else
<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                <h6 class="text-lg font-semibold text-slate-700">Mis recordatorios</h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom">
                        <tr>
                    <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 opacity-70 border-b border-gray-200">Fecha</th>
                    <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 opacity-70 border-b border-gray-200">Tipo</th>
                    <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 opacity-70 border-b border-gray-200">Veterinario</th>
                    <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 opacity-70 border-b border-gray-200">Descripción</th>
                    <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 opacity-70 border-b border-gray-200">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($historiales as $h)
                    <tr>
                        <td class="p-4 text-center align-middle bg-transparent border-b whitespace-nowrap">
                          <span class="text-sm font-semibold text-slate-700">
                              {{ $h->fecha->format('Y-m-d') }}</span>
                        </td>
                        <td class="p-4 text-center align-middle bg-transparent border-b whitespace-nowrap">
                          <span class="text-sm font-semibold text-slate-700">
                            {{ $h->tipo }}
                          </span>
                        </td>
                        <td class="p-4 text-center max-w-[200px] align-middle bg-transparent border-b whitespace-nowrap">
                          <span class="text-sm block truncate font-semibold text-slate-700">
                              {{ $h->veterinario }}</span>
                        </td>
                        <td class="p-4 text-center max-w-[200px] align-middle bg-transparent border-b whitespace-nowrap">
                          <span class="text-sm block truncate font-semibold text-slate-700">
                              {{ $h->descripcion }}</span>
                        </td>
                        <td class="p-4 text-center align-middle bg-transparent border-b whitespace-nowrap">
                            <a href="{{ route('mascotas.historial.show', [$mascota, $h]) }}"
                               class="mr-2 inline-block px-3 py-1 font-bold text-center bg-gradient-to-tl from-green-600 to-lime-400 uppercase align-middle transition-all rounded-md cursor-pointer text-xs ease-soft-in tracking-tight-soft shadow-sm bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                👁️ Ver</a>
                            <a href="{{ route('mascotas.historial.edit', [$mascota, $h]) }}"
                               class="mr-2 inline-block px-3 py-1 font-bold text-center bg-gradient-to-tl from-blue-600 to-cyan-400 uppercase align-middle transition-all rounded-md cursor-pointer text-xs ease-soft-in tracking-tight-soft shadow-sm bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                ✏️ Editar</a>
                                <a href="{{ route('mascotas.historial.destroy', [$mascota, $h]) }}"
                                   class="mr-2 inline-block px-3 py-1 font-bold text-center bg-gradient-to-tl from-red-600 to-rose-400 uppercase align-middle transition-all rounded-md cursor-pointer text-xs ease-soft-in tracking-tight-soft shadow-sm bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                    ❌ Eliminar</a>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    @endif
                </div>
            </div>
        </div>
    </div>
</div>
    <br>
    <a href="{{ route('mascotas.index') }}">← Volver a mascotas</a>
</div>
@endsection
