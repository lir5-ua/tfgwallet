
@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
    <h1>{{ isset($mascota) ? 'Editar Mascota' : 'Crear Mascota' }}</h1>


    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($mascota) ? route('mascotas.update', $mascota->id) : route('mascotas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($mascota))
        @method('PUT')
        @endif

        <button type="button" onclick="abrirModal('import')" class="inline-block px-6 py-2 font-bold text-left text-white uppercase transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-purple-700 to-pink-500 leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">Seleccionar imagen</button>

        <div class="fixed right-300 hidden overflow-x-hidden overflow-y-auto transition-opacity ease-linear opacity-0 z-sticky outline-0" id="import" aria-hidden="true">
            <div class="relative w-auto m-2 transition-transform duration-300 pointer-events-none sm:m-7 sm:max-w-125 sm:mx-auto lg:mt-48 ease-soft-out -translate-y-13">
                <div class="relative flex flex-col w-full bg-white border border-solid pointer-events-auto dark:bg-gray-950 bg-clip-padding border-black/20 rounded-xl outline-0">
                    <div class="flex items-center justify-between p-4 border-b border-solid shrink-0 border-slate-100 rounded-t-xl">
                        <h5 class="mb-0 leading-normal dark:text-white" id="ModalLabel">Importar Imagen</h5>
                        <i class="ml-4 fas fa-upload"></i>
                        <button type="button" onclick="cerrarModal('import')" class="fa fa-close w-4 h-4 ml-auto box-content p-2 text-black dark:text-white border-0 rounded-1.5 opacity-50 cursor-pointer -m-2"></button>

                    </div>
                    <div class="relative flex-auto p-4">
                        <p>Selecciona una fotografía de tu mascota.</p>
                        <input type="file" name="imagen" id="archivo" accept=".jpg, .jpeg, .png"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
              file:rounded-lg file:border-0
              file:text-sm file:font-semibold
              file:bg-gradient-to-tl file:from-purple-700 file:to-pink-500
              file:text-white hover:file:opacity-80
              dark:file:bg-gray-800 dark:file:text-white
              cursor-pointer" />

                        <div class="min-h-6 pl-7 mb-0.5 block">
                            <input class="w-5 h-5 ease-soft -ml-7 rounded-1.4 checked:bg-gradient-to-tl checked:from-gray-900 checked:to-slate-800 after:text-xxs after:font-awesome after:duration-250 after:ease-soft-in-out duration-250 relative float-left mt-1 cursor-pointer appearance-none border border-solid border-slate-150 bg-white bg-contain bg-center bg-no-repeat align-top transition-all after:absolute after:flex after:h-full after:w-full after:items-center after:justify-center after:text-white after:opacity-0 after:transition-all after:content-['\f00c'] checked:border-0 checked:border-transparent checked:bg-transparent checked:after:opacity-100" type="checkbox" value="1" name="condiciones" id="importCheck" checked="">
                            <label class="inline-block mb-2 ml-1 font-bold cursor-pointer select-none text-xs text-slate-700 dark:text-white/80" for="importCheck">Acepto los términos y condiciones</label>
                            @error('condiciones')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center justify-end p-3 border-t border-solid shrink-0 border-slate-100 rounded-b-xl">
                        <button type="button" onclick="cerrarModal('import')" class="inline-block px-8 py-2 m-1 mb-4 text-xs font-bold text-center text-white uppercase align-middle transition-all border-0 rounded-lg cursor-pointer ease-soft-in leading-pro tracking-tight-soft bg-gradient-to-tl from-purple-700 to-pink-500 shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85">Subir</button>
                    </div>
                </div>
            </div>
        </div>
        <br><br>

        <label for="nombre">Nombre:</label><br>
        <input type="text" name="nombre" value="{{ old('nombre', $mascota->nombre ?? '') }}" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-1 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none">
        @auth
        @if(auth()->user()->es_admin)
        <label for="user_id">ID Usuario:</label><br>
        <input type="number" name="user_id" value="{{ old('user_id', $mascota->user_id ?? '') }}">
        @else
        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
        @endif
        @endauth

        <br>

        <label for="especie">Especie:</label><br>
        <div class="relative inline-block w-64">
            <button type="button" id="dropdownEspecieBtn" onclick="toggleDropdownEspecie()"
                    class="inline-block w-full px-6 py-3 font-bold text-left text-white uppercase transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-purple-700 to-pink-500 leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                <span id="dropdownEspecieText">{{ collect($especies)->firstWhere('value', old('especie', $mascota->especie ?? ''))['label'] ?? '-- Selecciona especie --' }}</span>
            </button>

            <ul id="dropdownEspecieMenu"
                class="z-10 hidden absolute w-full mt-2 rounded-lg bg-white shadow-soft-md text-sm text-slate-700 dark:text-white">
                @foreach ($especies as $especie)
                <li>
                    <a href="javascript:void(0);"
                       onclick="seleccionarEspecie(event, '{{ $especie['value'] }}', '{{ $especie['label'] }}')"
                       class="block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600">
                        {{ $especie['label'] }}
                    </a>
                </li>
                @endforeach
            </ul>

            <!-- input oculto para enviar el valor seleccionado -->
            <input type="hidden" name="especie" id="especie" value="{{ old('especie', $mascota->especie ?? '') }}">
        </div>
        <br><br>

        <label for="raza">Raza:</label><br>
        <div class="relative inline-block w-64 mt-4">
            <button id="dropdownRazaBtn" onclick="toggleDropdownRaza()" type="button"
                    class="inline-block w-full px-6 py-3 font-bold text-left text-white uppercase transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-purple-700 to-pink-500 leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                <span id="dropdownRazaText">{{ old('raza') ?? '-- Selecciona raza --' }}</span>
            </button>

            <ul id="dropdownRazaMenu"
                class="z-10 hidden absolute w-full mt-2 rounded-lg bg-white shadow-soft-md text-sm text-slate-700 dark:text-white max-h-60 overflow-y-auto">
                <!-- Se rellenará dinámicamente -->
            </ul>

            <input type="hidden" name="raza" id="raza" value="{{ old('raza') }}">
        </div>
        <br><br>

        <label for="sexo">Sexo:</label><br>
        <div class="relative inline-block w-64 mt-4">
            <button id="dropdownSexoBtn" onclick="toggleDropdownSexo()" type="button"
                    class="inline-block w-full px-6 py-3 font-bold text-left text-white uppercase transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-purple-700 to-pink-500 leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                <span id="dropdownSexoText">{{ old('sexo') ? ucfirst(old('sexo')) : '-- Selecciona sexo --' }}</span>
            </button>

            <ul id="dropdownSexoMenu"
                class="z-10 hidden absolute w-full mt-2 rounded-lg bg-white shadow-soft-md text-sm text-slate-700 dark:text-white max-h-60 overflow-y-auto">
                @foreach ($sexos as $sexo)
                <li>
                    <a href="javascript:void(0);"
                       onclick="seleccionarSexo(event, '{{ $sexo->value }}', '{{ ucfirst($sexo->value) }}')"
                       class="block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600">
                        {{ ucfirst($sexo->value) }}
                    </a>
                </li>
                @endforeach
            </ul>

            <!-- input oculto para enviar el valor seleccionado -->
            <input type="hidden" name="sexo" id="sexo" value="{{ old('sexo') }}">
        </div>
        <br><br>

        <label for="fecha_nacimiento">Fecha de nacimiento:</label><br>
        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $mascota->fecha_nacimiento ?? '') }}" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-1 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none"><br><br>

        <label for="notas">Notas:</label><br>
        <textarea name="notas" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-1 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none">{{ old('notas', $mascota->notas ?? '') }}</textarea><br><br>

        <button type="submit" class="bg-green-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
            {{ isset($mascota) ? '✏️ Actualizar Mascota' : '💾 Guardar Mascota' }}
        </button>
        <a href="{{ session('return_to_after_update', route('usuarios.show', auth()->user())) }}"
           class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
            Volver
        </a>
    </form>
</div>

<!-- Script desplegable Especies-->
<script>
    function toggleDropdownEspecie() {
        const menu = document.getElementById('dropdownEspecieMenu');
        menu.classList.toggle('hidden');
    }

    function seleccionarEspecie(event, valor, texto) {
        event.stopPropagation();
        event.preventDefault();
        document.getElementById('especie').value = valor;
        document.getElementById('dropdownEspecieText').textContent = texto;
        document.getElementById('dropdownEspecieMenu').classList.add('hidden');

        if (typeof actualizarRazas === 'function') {
            actualizarRazas();
        }
    }

    // Cierre al hacer clic fuera
    document.addEventListener('click', function (event) {
        const menu = document.getElementById('dropdownEspecieMenu');
        const btn = document.getElementById('dropdownEspecieBtn');

        if (!menu.contains(event.target) && !btn.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
</script>


<!-- VENTANA AUXILIAR FOTO -->
<script>
    function abrirModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('hidden', 'opacity-0');
        modal.classList.add('block', 'opacity-100');
        modal.setAttribute('aria-hidden', 'false');
    }

    function cerrarModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('block', 'opacity-100');
        modal.classList.add('hidden', 'opacity-0');
        modal.setAttribute('aria-hidden', 'true');
    }
</script>
<!-- SUBIR FOTO -->
<script>
    function abrirModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('hidden', 'opacity-0');
        modal.classList.add('block', 'opacity-100');
        modal.setAttribute('aria-hidden', 'false');
    }

    function cerrarModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('block', 'opacity-100');
        modal.classList.add('hidden', 'opacity-0');
        modal.setAttribute('aria-hidden', 'true');
    }
</script>
<!-- CARGAR RAZAS -->

<script>
    const razasPorEspecie = {!! json_encode($razasPorEspecie) !!};

    function actualizarRazas() {
        const especie = document.getElementById('especie').value;
        const razaMenu = document.getElementById('dropdownRazaMenu');
        const razaText = document.getElementById('dropdownRazaText');
        const razaInput = document.getElementById('raza');

        razaMenu.innerHTML = ''; // Limpia las opciones anteriores
        razaText.textContent = '-- Selecciona raza --';
        razaInput.value = '';

        if (especie && razasPorEspecie[especie]) {
            razasPorEspecie[especie].forEach(function(raza) {
                const li = document.createElement('li');
                li.innerHTML = `<a href="javascript:void(0);" onclick="seleccionarRaza(event, '${raza}')"
                class="block px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600">${raza}</a>`;
                razaMenu.appendChild(li);
            });
            const oldRaza = "{{ old('raza') }}";
            if (oldRaza) {
                document.getElementById('raza').value = oldRaza;
                document.getElementById('dropdownRazaText').textContent = oldRaza;
            }
        }
    }

    function seleccionarRaza(event, raza) {
        event.stopPropagation();
        event.preventDefault();
        document.getElementById('raza').value = raza;
        document.getElementById('dropdownRazaText').textContent = raza;
        document.getElementById('dropdownRazaMenu').classList.add('hidden');
    }

</script>
<!-- SELECCIONAR RAZA-->
<script>
    function toggleDropdownRaza() {
        const menu = document.getElementById('dropdownRazaMenu');
        menu.classList.toggle('hidden');
    }

    // Cierre al hacer clic fuera del menú de raza
    document.addEventListener('click', function (event) {
        const menu = document.getElementById('dropdownRazaMenu');
        const btn = document.getElementById('dropdownRazaBtn');

        if (!menu.contains(event.target) && !btn.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
</script>
<!-- SELECCIONAR SEXO-->
<script>
    function toggleDropdownSexo() {
        const menu = document.getElementById('dropdownSexoMenu');
        menu.classList.toggle('hidden');
    }

    function seleccionarSexo(event, valor, texto) {
        event.stopPropagation();
        event.preventDefault();
        document.getElementById('sexo').value = valor;
        document.getElementById('dropdownSexoText').textContent = texto;
        document.getElementById('dropdownSexoMenu').classList.add('hidden');
    }

    // Cierra el dropdown de sexo si se hace clic fuera
    document.addEventListener('click', function (event) {
        const menu = document.getElementById('dropdownSexoMenu');
        const btn = document.getElementById('dropdownSexoBtn');

        if (!menu.contains(event.target) && !btn.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
</script>





@endsection
