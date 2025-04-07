@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow-md rounded p-6 mt-8">
    <h1 class="text-2xl font-bold mb-4">Detalles del Recordatorio</h1>
<ul>
    <li><strong>Título:</strong> {{ ucfirst($recordatorio->titulo) }}</li>
    <li><strong>Fecha:</strong> {{ $recordatorio->fecha->format('d/m/Y') }}</li>
    <li><strong>Descripción:</strong> {{ ucfirst($recordatorio->descripcion)}}</li>
    <li><strong>Estado:</strong>
                @if ($recordatorio->realizado)
                    <span class="text-green-600 font-semibold">Realizado</span>
                @else
                    <span class="text-red-600 font-semibold">Pendiente</span>
                @endif
    <li><strong>Mascota:</strong> {{ $recordatorio->mascota->nombre}}</p></li>
    </ul>
    <div class="flex space-x-4 mt-6">
        <a href="{{ route('recordatorios.edit', $recordatorio) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
            Editar
        </a>

        <form action="{{ route('recordatorios.destroy', $recordatorio) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este recordatorio?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                Eliminar
            </button>
        </form>

        <a href="{{ url()->previous() }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
            Volver
        </a>
    </div>
</div>
@endsection
