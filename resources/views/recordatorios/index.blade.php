@extends('layouts.gestion')
@section('content')
<ul>
@forelse ($recordatorios as $nombreMascota => $listaRecordatorios)
    <h2 class="text-xl font-bold mb-4">Próximos recordatorios para {{ $nombreMascota }}</h2>

    @foreach ($listaRecordatorios as $recordatorio)
        <li class="border-b py-2">
            <strong>
                <a href="{{ route('recordatorios.show', $recordatorio) }}" class="text-blue-600 hover:underline">
                    {{ $recordatorio->titulo }}
                </a>
            </strong><br>
            <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($recordatorio->fecha)->format('d/m/Y') }}</span><br>
            <span>{{ $recordatorio->descripcion }}</span>
            <form action="{{ route('recordatorios.visto', $recordatorio) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="text-blue-600 hover:underline">👁️ Visto</button>
            </form>

        </li>
    @endforeach
@empty
    <p>No hay recordatorios próximos.</p>
@endforelse
</ul>
@endsection
