@extends('layouts.app')

@section('title', 'Mis recordatorios')

@section('content')
<div class="flex flex-wrap items-center space-x-4 mb-4">


    <!-- Formulario de búsqueda -->
    <form method="GET" action="{{ route('recordatorios.personales', ['usuario' => $usuario]) }}" class="flex items-center space-x-2">
        <!-- Input de búsqueda con icono -->
        <!-- Botón Crear -->
        <a href="{{ route('recordatorios.create', ['usuario_id' => $usuario->id]) }}"
           class="h-10 px-6 py-2 font-bold text-center bg-gradient-to-tl from-green-600 to-lime-400 uppercase rounded-lg text-xs text-white flex items-center justify-center">
            Crear
        </a>
        <!-- Botón Calendario -->
        <a href="{{ route('recordatorios.calendario', ['usuario' => $usuario->id]) }}"
           class="h-10 px-6 py-2 font-bold text-center bg-gradient-to-tl from-purple-600 to-pink-400 uppercase rounded-lg text-xs text-white flex items-center justify-center">
            📅 Calendario
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
        <a href="{{ route('recordatorios.personales', ['usuario' => $usuario->id]) }}"
           class="h-10 px-6 py-2 font-bold bg-gradient-to-tl from-slate-600 to-slate-300 uppercase rounded-lg text-xs text-white flex items-center justify-center">
            Resetear
        </a>
    </form>
</div>
<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white dark:bg-slate-400 dark:text-white shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white dark:bg-slate-400 dark:text-white rounded-t-2xl">
                <h6 class="text-lg font-semibold text-slate-700 dark:text-white">Mis recordatorios</h6>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500 dark:text-white">
                        <thead class="align-bottom">
                        <tr>
                            <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 dark:text-white opacity-70 border-b border-gray-200">Mascota</th>
                            <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 dark:text-white opacity-70 border-b border-gray-200">Título</th>
                            <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 dark:text-white opacity-70 border-b border-gray-200">Fecha</th>
                            <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 dark:text-white opacity-70 border-b border-gray-200">Estado</th>
                            <th class="px-6 py-3 text-center uppercase text-xxs font-bold text-slate-400 dark:text-white opacity-70 border-b border-gray-200">Acciones</th>
                        </tr>
                        <tr>
                            <form method="GET" action="{{ route('recordatorios.personales', ['usuario' => $usuario->id]) }}">

                            <th class="text-center px-6 py-2">
                                    <select name="mascota" class="w-32 text-xs h-8 px-2 py-1 border border-gray-300 rounded dark:bg-slate-600 dark:text-white dark:border-gray-500">
                                        <option value="">Todas</option>
                                        @foreach ($mascotasUnicas as $mascota)
                                        <option value="{{ $mascota }}" {{ request('mascota') === $mascota ? 'selected' : '' }}>
                                        {{ $mascota }}
                                        </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th class="text-center px-6 py-2">
                                    <input type="text" name="titulo" class="w-32 text-xs h-8 px-2 py-1 border border-gray-300 rounded dark:bg-slate-600 dark:text-white dark:border-gray-500"
                                           placeholder="Buscar título..." value="{{ request('titulo') }}">
                                </th>
                                <th class="text-center px-6 py-2">
                                    <input type="date" name="fecha" class="w-32 text-xs h-8 px-2 py-1 border border-gray-300 rounded dark:bg-slate-600 dark:text-white dark:border-gray-500"
                                           value="{{ request('fecha') }}">
                                </th>
                                <th class="px-6 py-2 text-center">
                                    <select name="estado" class="w-32 text-xs h-8 px-2 py-1 border border-gray-300 rounded dark:bg-slate-600 dark:text-white dark:border-gray-500">
                                        <option value="">Todos</option>
                                        <option value="0" {{ request('estado') === '0' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="1" {{ request('estado') === '1' ? 'selected' : '' }}>Hecho</option>
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
                        @forelse($recordatorios as $recordatorio)
                        <tr>
                            <td class="p-4 text-center max-w-[200px] align-middle bg-transparent border-b whitespace-nowrap">
                                    <a href="{{ route('mascotas.show', $recordatorio->mascota->id) }}" class="text-sm block truncate font-semibold text-slate-700 dark:text-white hover:text-blue-500">
                                        {{ $recordatorio->mascota->nombre ?? 'Sin mascota' }}
                                    </a>
                            </td>
                            <td class="p-4 text-center max-w-[200px] align-middle bg-transparent border-b whitespace-nowrap">
                                <span class="text-sm block truncate text-slate-600 dark:text-white">{{ $recordatorio->titulo }}</span>
                            </td>
                            <td class="p-4 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                <span class="text-sm text-slate-600 dark:text-white">{{ $recordatorio->fecha->format('d/m/Y') }}</span>
                            </td>
                            <td class="p-4 text-center align-middle text-center bg-transparent border-b whitespace-nowrap">
                                <form action="{{ route('recordatorios.visto', $recordatorio) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="realizado" onchange="this.form.submit()"
                                            class="px-2 py-1 text-xs font-bold uppercase rounded-lg
                                            {{ $recordatorio->realizado
                                                ? 'bg-gradient-to-tl from-green-500 to-emerald-400 text-white'
                                                : 'bg-gradient-to-tl from-red-600 to-rose-500 text-white' }}">
                                        <option value="0" {{ !$recordatorio->realizado ? 'selected' : '' }}>Pendiente</option>
                                        <option value="1" {{ $recordatorio->realizado ? 'selected' : '' }}>Hecho</option>
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
                            <td colspan="5" class="p-4 text-center text-sm text-slate-500 dark:text-white">
                                No tienes recordatorios.
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
@endsection
