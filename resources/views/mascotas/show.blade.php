@extends('layouts.gestion')

@section('content')
<div class="container">
    <h1>{{ $mascota->nombre }}</h1>

    {{-- Imagen de la mascota (solo si tienes un campo tipo $mascota->imagen) --}}
    @if($mascota->imagen)
        <img src="{{ asset('storage/' . $mascota->imagen) }}" alt="Imagen de {{ $mascota->nombre }}" style="max-width: 300px;">
    @else
        <p>No hay imagen disponible.</p>
    @endif

    <ul>
        <li><strong>Especie:</strong> {{ ucfirst($mascota->especie->value) }}</li>
        <li><strong>Raza:</strong> {{ $mascota->raza }}</li>
        <li><strong>Sexo:</strong> {{ $mascota->sexo?->value }}</li>
        <li><strong>Fecha de nacimiento:</strong> {{ $mascota->fecha_nacimiento?->format('d/m/Y') }}</li>
        <li><strong>Notas:</strong> {{ $mascota->notas }}</li>
        <li><strong>Usuario responsable:</strong> {{ $mascota->usuario->name }}</li>
    </ul>

    <hr>

    <h2>Historial médico</h2>
    @if($mascota->historial->isEmpty())
        <p>Esta mascota no tiene historial médico registrado.</p>
    @else
        <ul>
            @foreach($mascota->historial as $registro)
                <li>
                    <strong>{{ $registro->fecha->format('d/m/Y') }}</strong> -
                    {{ $registro->tipo }}: {{ $registro->descripcion }}
                    (Veterinario: {{ $registro->veterinario }})
                </li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('mascotas.index') }}">← Volver al listado</a>
</div>
@endsection
