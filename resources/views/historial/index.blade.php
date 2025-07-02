@extends('layouts.app')

@section('title', 'historialMedico')

@section('content')
<div class="flex flex-wrap items-center space-x-4 mb-4">
    <!-- Formulario de filtros avanzado -->
    <form method="GET" action="{{ isset($mascota) && $mascota ? route('mascotas.historial.index', $mascota->hashid) : route('historial.index') }}" class="flex flex-wrap items-end gap-2 bg-gray-50 p-4 rounded-lg shadow">
        <div>
            <label for="fecha" class="block text-xs font-semibold text-slate-600 mb-1">Fecha</label>
            <input type="date" name="fecha" id="fecha" value="{{ $filtros['fecha'] ?? '' }}" class="px-2 py-1 border border-gray-300 rounded text-sm">
        </div>
        <div>
            <label for="tipo" class="block text-xs font-semibold text-slate-600 mb-1">Tipo</label>
            <select name="tipo" id="tipo" class="px-2 py-1 border border-gray-300 rounded text-sm">
                <option value="">Todos</option>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo->value }}" {{ (isset($filtros['tipo']) && $filtros['tipo'] === $tipo->value) ? 'selected' : '' }}>{{ $tipo->value }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="veterinario" class="block text-xs font-semibold text-slate-600 mb-1">Veterinario</label>
            <input type="text" name="veterinario" id="veterinario" value="{{ $filtros['veterinario'] ?? '' }}" placeholder="Nombre..." class="px-2 py-1 border border-gray-300 rounded text-sm">
        </div>
        <div class="flex gap-2 mt-4 md:mt-0">
            <button type="submit" class="h-10 px-4 py-2 font-bold bg-gradient-to-tl from-blue-600 to-cyan-400 uppercase rounded-lg text-xs text-white flex items-center justify-center">Filtrar</button>
            @if(isset($mascota) && $mascota)
                <a href="{{ route('mascotas.historial.index', $mascota->hashid) }}" class="h-10 px-4 py-2 font-bold bg-gradient-to-tl from-slate-600 to-slate-300 uppercase rounded-lg text-xs text-white flex items-center justify-center">Resetear</a>
            @else
                <a href="{{ route('historial.index') }}" class="h-10 px-4 py-2 font-bold bg-gradient-to-tl from-slate-600 to-slate-300 uppercase rounded-lg text-xs text-white flex items-center justify-center">Resetear</a>
            @endif
        </div>
        <div class="ml-auto">
            <a href="{{ isset($mascota) && $mascota ? route('mascotas.historial.create', $mascota->hashid) : '#' }}"
               class="h-10 px-6 py-2 font-bold text-center bg-gradient-to-tl from-green-600 to-lime-400 uppercase rounded-lg text-xs text-white flex items-center justify-center {{ isset($mascota) && $mascota ? '' : 'pointer-events-none opacity-50' }}">
                Crear
            </a>
        </div>
    </form>
</div>

    @if ($historiales->isEmpty())
        <p class="dark:text-white">No hay entradas en el historial médico.</p>
    @else
<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white dark:bg-slate-400 dark:text-white shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white dark:bg-slate-400 dark:text-white rounded-t-2xl">
                <h6 class="text-lg font-semibold text-slate-700 dark:text-white">Historial médico</h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500 dark:text-white">
                        <thead class="align-bottom">
                        <tr>
                    <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 dark:text-white opacity-70 border-b border-gray-200">Fecha</th>
                    <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 dark:text-white opacity-70 border-b border-gray-200">Tipo</th>
                    <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 dark:text-white opacity-70 border-b border-gray-200">Veterinario</th>
                    <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 dark:text-white opacity-70 border-b border-gray-200">Descripción</th>
                    <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 dark:text-white opacity-70 border-b border-gray-200">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($historiales as $h)
                    <tr>
                        <td class="p-4 text-center align-middle bg-transparent border-b whitespace-nowrap">
                          <span class="text-sm font-semibold text-slate-700 dark:text-white">
                              {{ $h->fecha->format('Y-m-d') }}</span>
                        </td>
                        <td class="p-4 text-center align-middle bg-transparent border-b whitespace-nowrap">
                          <span class="text-sm font-semibold text-slate-700 dark:text-white">
                            {{ $h->tipo }}
                          </span>
                        </td>
                        <td class="p-4 text-center max-w-[200px] align-middle bg-transparent border-b whitespace-nowrap">
                          <span class="text-sm block truncate font-semibold text-slate-700 dark:text-white">
                              {{ $h->veterinario }}</span>
                        </td>
                        <td class="p-4 text-center max-w-[200px] align-middle bg-transparent border-b whitespace-nowrap">
                          <span class="text-sm block truncate font-semibold text-slate-700 dark:text-white">
                              {{ $h->descripcion }}</span>
                        </td>
                        <td class="p-4 text-center align-middle bg-transparent border-b whitespace-nowrap">
                            <a href="{{ isset($mascota) && $mascota ? route('mascotas.historial.show', [$mascota->hashid, $h->hashid]) : '#' }}"
                               class="mr-2 inline-block px-3 py-1 font-bold text-center bg-gradient-to-tl from-green-600 to-lime-400 uppercase align-middle transition-all rounded-md cursor-pointer text-xs ease-soft-in tracking-tight-soft shadow-sm bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white {{ isset($mascota) && $mascota ? '' : 'pointer-events-none opacity-50' }}">
                                👁️ Ver</a>
                            <a href="{{ isset($mascota) && $mascota ? route('mascotas.historial.edit', [$mascota->hashid, $h->hashid]) : '#' }}"
                               class="mr-2 inline-block px-3 py-1 font-bold text-center bg-gradient-to-tl from-blue-600 to-cyan-400 uppercase align-middle transition-all rounded-md cursor-pointer text-xs ease-soft-in tracking-tight-soft shadow-sm bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white {{ isset($mascota) && $mascota ? '' : 'pointer-events-none opacity-50' }}">
                                ✏️ Editar</a>
                                <form action="{{ isset($mascota) && $mascota ? route('mascotas.historial.destroy', [$mascota->hashid, $h->hashid]) : '#' }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="mr-2 inline-block px-3 py-1 font-bold text-center bg-gradient-to-tl from-red-600 to-rose-400 uppercase align-middle transition-all rounded-md cursor-pointer text-xs ease-soft-in tracking-tight-soft shadow-sm bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white {{ isset($mascota) && $mascota ? '' : 'pointer-events-none opacity-50' }}">
                                        ❌ Eliminar</button>
                                </form>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
        @if(isset($mascota) && $mascota)
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mt-4">
                <div class="text-xs text-slate-500 dark:text-white mb-2 md:mb-0">
                    Mostrando {{ $historiales->firstItem() }}-{{ $historiales->lastItem() }} de {{ $historiales->total() }} entradas
                </div>
                <div>
                    {{ $historiales->links() }}
                </div>
            </div>
        @endif
                </div>
            </div>
        </div>
    </div>
</div>
    @endif
    <br>
    <a href="{{ route('mascotas.index') }}" class="dark:text-white">← Volver a mascotas</a>
</div>
@endsection
