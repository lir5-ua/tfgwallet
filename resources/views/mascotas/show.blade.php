@extends('layouts.app')

@section('content')
<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
        <div
            class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                <div class="flex items-center space-x-4">
                    <img
                        src="{{ $mascota->imagen_url }}"
                        class="h-12 w-12 rounded-xl object-cover"
                        alt="{{ $mascota->nombre }}">

                    <h6 class="text-lg font-semibold text-slate-700">{{ $mascota->nombre }}</h6>
                </div>

            </div>
            <div class="flex flex-wrap -mx-3">
                <div class="flex-none w-full max-w-full px-3">
                    <div
                        class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">


                        <ul>
                            <li><strong>Especie:</strong> {{ ucfirst($mascota->especie->value) }}</li>
                            <li><strong>Raza:</strong> {{ $mascota->raza }}</li>
                            <li><strong>Sexo:</strong> {{ $mascota->sexo?->value }}</li>
                            <li><strong>Fecha de nacimiento:</strong> {{ $mascota->fecha_nacimiento?->format('d/m/Y') }}
                            </li>
                            <li><strong>Notas:</strong> {{ $mascota->notas }}</li>
                            <li><strong>Usuario responsable:</strong> {{ $mascota->usuario->name }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<hr>
<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
        <div
            class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                <h6 class="text-lg font-semibold text-slate-700">Historial médico</h6>
                <!-- Botón Crear -->
                <div class="flex items-center space-x-2">
                    <a href="{{ route('mascotas.historial.create', $mascota) }}"
                    class="h-10 px-6 py-2 font-bold text-center bg-gradient-to-tl from-green-600 to-lime-400 uppercase rounded-lg text-xs text-white flex items-center justify-center">
                    Crear
                </a>
                </div>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom">
                        <tr>
                            <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 opacity-70 border-b border-gray-200">
                                Fecha
                            </th>
                            <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 opacity-70 border-b border-gray-200">
                                Tipo
                            </th>
                            <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 opacity-70 border-b border-gray-200">
                                Veterinario
                            </th>
                            <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 opacity-70 border-b border-gray-200">
                                Descripción
                            </th>
                            <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 opacity-70 border-b border-gray-200">
                                Acciones
                            </th>
                        </tr>
                        <tr>
                            <form method="GET" action="">
                                <th class="text-center px-6 py-2">
                                    <input type="date" name="fecha"
                                           class="w-32 text-xs h-8 px-2 py-1 border border-gray-300 rounded"
                                           value="{{ request('fecha') }}">
                                </th>
                                <th class="text-center px-6 py-2">
                                    <select name="tipo"
                                            class="w-32 text-xs h-8 px-2 py-1 border border-gray-300 rounded">
                                        <option value="">Todos</option>
                                        @foreach(\App\Enums\TipoHistorial::cases() as $tipo)
                                            <option value="{{ $tipo->value }}" {{ request('tipo') === $tipo->value ? 'selected' : '' }}>{{ $tipo->value }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th class="text-center px-6 py-2">
                                    <input type="text" name="veterinario"
                                           class="w-32 text-xs h-8 px-2 py-1 border border-gray-300 rounded"
                                           placeholder="Veterinario..."
                                           value="{{ request('veterinario') }}">
                                </th>
                                <th></th>
                                <th class="px-6 py-2 text-center">
                                    <button type="submit"
                                            class="px-4 py-2 text-xs font-bold text-white bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg uppercase">
                                        Filtrar
                                    </button>
                                    <a href="{{ route('mascotas.show', $mascota) }}"
                                       class="px-4 py-2 text-xs font-bold text-white bg-gradient-to-tl from-slate-600 to-slate-300 rounded-lg uppercase">
                                        Resetear
                                    </a>
                                </th>
                            </form>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($historialFiltrado as $h)
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
                            <td class="p-4 text-center  max-w-[200px] align-middle bg-transparent border-b whitespace-nowrap">
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
                                <form action="{{ route('mascotas.historial.destroy', [$mascota, $h]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="mr-2 inline-block px-3 py-1 font-bold text-center bg-gradient-to-tl from-red-600 to-rose-400 uppercase align-middle transition-all rounded-md cursor-pointer text-xs ease-soft-in tracking-tight-soft shadow-sm bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                        ❌ Eliminar
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">Esta mascota no tiene historial
                                médico registrado.
                            </td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TABLA RECORDATORIOS -->
<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
        <div
            class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                <h6 class="text-lg font-semibold text-slate-700">Recordatorios pendientes</h6>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('mascotas.recordatorios.create', $mascota) }}"
                       class="h-10 px-6 py-2 font-bold text-center bg-gradient-to-tl from-green-600 to-lime-400 uppercase rounded-lg text-xs text-white flex items-center justify-center">
                        Crear
                    </a>
                </div>
            </div>

            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                    <table
                        class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom">
                        <tr>
                            <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 opacity-70 border-b border-gray-200">
                                Título
                            </th>
                            <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 opacity-70 border-b border-gray-200">
                                Fecha
                            </th>
                            <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 opacity-70 border-b border-gray-200">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 opacity-70 border-b border-gray-200">
                                Acciones
                            </th>
                        </tr>
                        <tr>
                            <form method="GET"
                                  action="{{ route('recordatorios.personales', ['usuario' => $mascota->usuario]) }}">

                                <th class="text-center px-6 py-2">
                                    <input type="text" name="titulo"
                                           class="w-32 text-xs h-8 px-2 py-1 border border-gray-300 rounded"
                                           placeholder="Buscar título..."
                                           value="{{ request('titulo') }}">
                                </th>
                                <th class="text-center px-6 py-2">
                                    <input type="date" name="fecha"
                                           class="w-32 text-xs h-8 px-2 py-1 border border-gray-300 rounded"
                                           value="{{ request('fecha') }}">
                                </th>
                                <th class="px-6 py-2 text-center">
                                    <select name="estado"
                                            class="w-32 text-xs h-8 px-2 py-1 border border-gray-300 rounded">
                                        <option value="">Todos</option>
                                        <option value="0" {{ request(
                                        'estado') === '0' ? 'selected' : ''
                                        }}>Pendiente</option>
                                        <option value="1" {{ request(
                                        'estado') === '1' ? 'selected' : ''
                                        }}>Hecho</option>
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
                        @forelse ($recordatorios as $recordatorio)
                        <tr>
                            <td class="p-4 text-center max-w-[200px] align-middle bg-transparent border-b whitespace-nowrap">
                                <span class="text-sm block truncate text-slate-600">{{ $recordatorio->titulo }}</span>
                            </td>
                            <td class="p-4 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                    <span
                                        class="text-sm text-slate-600">{{ $recordatorio->fecha->format('d/m/Y') }}</span>
                            </td>
                            <td class="p-4 text-center align-middle text-center bg-transparent border-b whitespace-nowrap">
                                <form
                                    action="{{ route('recordatorios.visto', $recordatorio) }}"
                                    method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="realizado" onchange="this.form.submit()"
                                            class="px-2 py-1 text-xs font-bold uppercase rounded-lg
                                            {{ $recordatorio->realizado
                                                ? 'bg-gradient-to-tl from-green-500 to-emerald-400 text-white'
                                                : 'bg-gradient-to-tl from-red-600 to-rose-500 text-white' }}">
                                        <option value="0" {{ !$recordatorio->realizado ?
                                            'selected' : '' }}>Pendiente
                                        </option>
                                        <option value="1" {{ $recordatorio->realizado ?
                                            'selected' : '' }}>Hecho
                                        </option>
                                    </select>
                                </form>
                            </td>

                            <td class="p-4 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                <a href="{{ route('recordatorios.show', $recordatorio) }}"
                                   class="mr-2 inline-block px-3 py-1 font-bold text-center bg-gradient-to-tl from-green-600 to-lime-400 uppercase align-middle transition-all rounded-md cursor-pointer text-xs ease-soft-in tracking-tight-soft shadow-sm bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                    👁️ Ver</a>
                                <a href="{{ route('recordatorios.edit', $recordatorio) }}"
                                   class="mr-2 inline-block px-3 py-1 font-bold text-center bg-gradient-to-tl from-blue-600 to-cyan-400 uppercase align-middle transition-all rounded-md cursor-pointer text-xs ease-soft-in tracking-tight-soft shadow-sm bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                    ✏️ Editar</a>

                                <form action="{{ route('recordatorios.destroy',$recordatorio) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('¿Estás seguro de que quieres eliminar este recordatorio?')"
                                            class="mr-2 inline-block px-3 py-1 font-bold text-center bg-gradient-to-tl from-red-600 to-rose-400 uppercase align-middle transition-all rounded-md cursor-pointer text-xs ease-soft-in tracking-tight-soft shadow-sm bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                        ❌ Eliminar
                                    </button>

                                </form>

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">Esta mascota no tiene historial
                                médico registrado.
                            </td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('mascotas.index') }}">← Volver al listado</a>
</div>
@endsection
