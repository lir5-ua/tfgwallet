@extends('layouts.app')

@section('title', 'historialMeidco')

@section('content')
<div class="container">
    @if (session('success'))
    <div style="color: green;">{{ session('success') }}</div>
    @endif
    <div class="flex flex-wrap items-center space-x-4 mb-4">


        <!-- Formulario de búsqueda -->
        <form method="GET" action="{{ route('usuarios.index') }}" class="flex items-center space-x-2">
            <!-- Input de búsqueda con icono -->
            <!-- Botón Crear -->
            <a href="{{ route('usuarios.create') }}"
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
            <a href="{{ route('usuarios.index') }}"
               class="h-10 px-6 py-2 font-bold bg-gradient-to-tl from-slate-600 to-slate-300 uppercase rounded-lg text-xs text-white flex items-center justify-center">
                Resetear
            </a>
        </form>
    </div>


    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div
                class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6 class="text-lg font-semibold text-slate-700">Usuarios</h6>
                </div>
                <div class="flex-auto px-0 pt-0 pb-2">
                    <div class="p-0 overflow-x-auto">
                        <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                            <thead class="align-bottom">
                            <tr>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Usuario
                                </th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Última sesión
                                </th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Acciones
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($usuarios as $usuario)
                            <tr>
                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <div class="flex px-2 py-1">
                                        <div>
                                            <img
                                                src="{{ $usuario->foto ? asset('storage/' . $usuario->foto) : asset('storage/default/defaultUser.jpg') }}"
                                                class="inline-flex items-center justify-center mr-4 text-sm text-white h-9 w-9 max-w-none rounded-xl">
                                        </div>
                                        <div class="flex flex-col justify-center">
                                            <h6 class="mb-0 text-sm leading-normal">
                                                <a href="{{ route('usuarios.show', ['usuario' => $usuario->id]) }}"
                                                   class="text-sm font-semibold text-slate-700 hover:text-blue-500">
                                                    {{ $usuario->name }}
                                                </a></h6>
                                            <p class="mb-0 text-xs leading-tight text-slate-400">{{ $usuario->email
                                                }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="p-4 text-sm text-center text-slate-500 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    {{ $usuario->ultima_conexion ? $usuario->ultima_conexion->format('d/m/Y H:i') : 'Nunca' }}
                                </td>
                                <td class="p-4 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <a href="{{ route('usuarios.edit', $usuario) }}"
                                       class="mr-3 inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-blue-600 to-cyan-400 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                        ✏️ Editar
                                    </a>

                                    <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('¿Eliminar este usuario?')"
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

    <div style="margin-top: 20px; display: flex; justify-content: center; flex-direction: column; align-items: center;">
        <x-pagination-info :paginator="$usuarios" itemName="usuarios" />
        {{ $usuarios->links() }}
    </div>

    <br>
    <a href="{{ route('mascotas.index') }}">← Volver a mascotas</a>
</div>
@endsection
</html>
