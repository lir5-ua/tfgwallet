
@extends('layouts.gestion')

@section('content')
<div class="container">
    <h1>Editar Mascota: {{ $mascota->nombre }}</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mascotas.update', $mascota) }}" method="POST"  enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label for="imagen">Foto:</label><br>
        <input type="file" name="imagen" id="imagen" accept="image/*"><br><br>

        <label for="nombre">Nombre:</label><br>
        <input type="text" name="nombre" value="{{ old('nombre', $mascota->nombre) }}"><br><br>

        <label for="nombre_usuario">Dueño:</label><br>
        <input type="text" name="nombre_usuario" value="{{ old('nombre_usuario', $mascota->usuario->name) }}"><br><br>

        <label for="especie">Especie:</label><br>
      <select id="especie" name="especie" onchange="actualizarRazas()">
          <option value="" {{ old('especie', $mascota->especie->value ?? null) ? '' : 'selected' }}>
              -- Selecciona especie --
          </option>

          @foreach ($especies as $especie)
              <option value="{{ $especie['value'] }}"
                  {{ old('especie', $mascota->especie->value ?? null) === $especie['value'] ? 'selected' : '' }}>
                  {{ $especie['label'] }}
              </option>
          @endforeach
      </select><br><br>


        <label for="raza">Raza:</label><br>
        <select id="raza" name="raza">
            <option value="">-- Selecciona raza --</option>
        </select><br><br>

        <label for="sexo">Sexo:</label><br>
        <select name="sexo" id="sexo" onchange="actualizarRazas()">
                    <option value="">-- Selecciona sexo --</option>
                    @foreach ($sexos as $sexo)
                    <!-- El operador ?-> hace que Laravel diga: "Si $mascota->sexo no es null, accede a ->value; si es null, devuélveme null sin error."-->
                    <!-- Devuélveme el valor anterior del campo sexo si lo hay (old('sexo')),y si no lo hay, usa el valor del modelo ($mascota->sexo?->value)."-->
                        <option value="{{ $sexo->value }}" @selected(old('sexo', $mascota->sexo?->value) === $sexo->value)>
                            {{ ucfirst($sexo->value) }}
                        </option>
                    @endforeach

                </select><br><br>

        <label for="fecha_nacimiento">Fecha de nacimiento:</label><br>
        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $mascota->fecha_nacimiento?->format('Y-m-d')) }}"><br><br>

        <label for="notas">Notas:</label><br>
        <textarea name="notas">{{ old('notas', $mascota->notas) }}</textarea><br><br>

        <button type="submit">💾 Guardar cambios</button>
    </form>
</div>

<script>
    const razasPorEspecie = @json($razasPorEspecie);

    function actualizarRazas() {
        const especie = document.getElementById('especie').value;
        const razaSelect = document.getElementById('raza');
        razaSelect.innerHTML = '<option value="">-- Selecciona raza --</option>';

        if (especie && razasPorEspecie[especie]) {
            razasPorEspecie[especie].forEach(function(raza) {
                const option = document.createElement('option');
                option.value = raza;
                option.textContent = raza;

                // Aquí marcamos como seleccionada si coincide
                if (raza === razaSeleccionada) {
                    option.selected = true;
                }

                razaSelect.appendChild(option);
            });
        }
    }

    // Valor actual para el caso de edición
    const razaSeleccionada = "{{ old('raza', $mascota->raza ?? '') }}";

    document.addEventListener('DOMContentLoaded', function () {
        actualizarRazas();
    });
</script>

@endsection
