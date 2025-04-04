
@extends('layouts.gestion')

@section('content')
<div class="container">
    <h1>Crear Mascota</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mascotas.store') }}" method="POST" enctype="multipart/form-data">

        @csrf

        <label for="imagen">Foto:</label>
                <input type="file" name="imagen" id="imagen" accept="image/*">

        <label for="nombre">Nombre:</label><br>
        <input type="text" name="nombre" value="{{ old('nombre') }}"><br><br>

        <label for="user_id">ID Usuario:</label><br>
        <input type="number" name="user_id" value="{{ old('user_id', 1) }}"><br><br>

        <label for="especie">Especie:</label><br>
        <select name="especie" id="especie" onchange="actualizarRazas()">
            <option value="">-- Selecciona especie --</option>
            @foreach ($especies as $especie)
                <option value="{{ $especie->value }}">{{ ucfirst($especie->value) }}</option>
            @endforeach
        </select><br><br>

        <label for="raza">Raza:</label><br>
        <select name="raza" id="raza">
            <option value="">-- Selecciona raza --</option>
        </select><br><br>

        <label for="sexo">Sexo:</label><br>
       <select name="sexo" id="sexo">
                   <option value="">-- Selecciona sexo --</option>
                   @foreach ($sexos as $sexo)
                       <option value="{{ $sexo->value }}">{{ ucfirst($sexo->value) }}</option>
                   @endforeach
               </select><br><br>

        <label for="fecha_nacimiento">Fecha de nacimiento:</label><br>
        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"><br><br>

        <label for="notas">Notas:</label><br>
        <textarea name="notas">{{ old('notas') }}</textarea><br><br>

        <button type="submit">💾 Guardar Mascota</button>
    </form>
</div>

<script>
    const razasPorEspecie = {
        perro: ['Labrador', 'Golden Retriever', 'Bulldog'],
        gato: ['Siamés', 'Persa', 'Maine Coon'],
        ave: ['Canario', 'Periquito'],
        pez: ['Betta', 'Guppy']
    };

    function actualizarRazas() {
        const especie = document.getElementById('especie').value;
        const razaSelect = document.getElementById('raza');
        razaSelect.innerHTML = '<option value="">-- Selecciona raza --</option>';

        if (especie && razasPorEspecie[especie]) {
            razasPorEspecie[especie].forEach(function(raza) {
                const option = document.createElement('option');
                option.value = raza;
                option.textContent = raza;
                razaSelect.appendChild(option);
            });
        }
    }
</script>
@endsection
