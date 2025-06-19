@extends('layouts.app')

@section('content')
<div class="bg-white text-black dark:bg-slate-800 dark:text-white p-4 rounded ease-soft-in-out relative h-full max-h-screen bg-gray-50 transition-all duration-200">
    <div class="w-full px-6 mx-auto dark:text-white">
        <!-- Header -->
        <div class="relative flex items-center p-0 mt-6 overflow-hidden bg-center bg-cover min-h-75 rounded-2xl"
             style="background-image: url('/assets/img/curved-images/curved0.jpg'); background-position-y: 50%">
            <span class="absolute inset-y-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-purple-700 to-pink-500 opacity-60"></span>
        </div>
        
        <div class="relative flex flex-col flex-auto min-w-0 p-4 mx-6 -mt-16 overflow-hidden break-words border-0 shadow-blur rounded-2xl bg-white/80 bg-clip-border backdrop-blur-2xl backdrop-saturate-200">
            <div class="flex flex-wrap -mx-3">
                <div class="flex-none w-auto max-w-full px-3">
                    <div class="text-base ease-soft-in-out h-18.5 w-18.5 relative inline-flex items-center justify-center rounded-xl text-white transition-all duration-200">
                        @if(isset($mascota) && $mascota->imagen)
                            <img src="{{ Storage::url($mascota->imagen) }}" 
                                 alt="Foto de {{ $mascota->nombre }}"
                                 class="w-full h-full rounded-xl object-cover">
                        @elseif(isset($mascota))
                            <img src="{{ asset('storage/mascotas/default_pet.jpg') }}" 
                                 alt="Imagen por defecto"
                                 class="w-full h-full rounded-xl object-cover">
                        @else
                            <img src="{{ asset('storage/mascotas/default_pet.jpg') }}" 
                                 alt="Imagen por defecto"
                                 class="w-full h-full rounded-xl object-cover">
                        @endif
                    </div>
                </div>
                <div class="flex-none w-auto max-w-full px-3 my-auto">
                    <div class="h-full">
                        <h5 class="mb-1">{{ isset($mascota) ? 'Editar Mascota' : 'Crear Nueva Mascota' }}</h5>
                        <p class="mb-0 text-sm text-slate-600">{{ isset($mascota) ? 'Actualiza la información de tu mascota' : 'Registra una nueva mascota en el sistema' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="w-full p-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto lg:w-8/12">
                <div class="relative flex flex-col h-full min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                        <h6 class="mb-0">Información de la Mascota</h6>
                    </div>
                    
                    <div class="flex-auto p-4">
                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                                <ul class="list-disc list-inside">
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

                            <!-- Imagen -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Foto de la mascota
                                </label>
                                
                                <!-- Mostrar imagen actual solo si estamos editando -->
                                @if(isset($mascota) && $mascota->imagen)
                                    <div class="mb-4">
                                        <img src="{{ Storage::url($mascota->imagen) }}" 
                                             alt="Foto actual de {{ $mascota->nombre }}"
                                             class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200">
                                        <p class="text-sm text-gray-600 mt-2">Imagen actual de la mascota</p>
                                    </div>
                                @elseif(isset($mascota))
                                    <div class="mb-4">
                                        <img src="{{ asset('storage/mascotas/default_pet.jpg') }}" 
                                             alt="Imagen por defecto"
                                             class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200">
                                        <p class="text-sm text-gray-600 mt-2">Imagen por defecto</p>
                                    </div>
                                @endif
                                
                                <button type="button" onclick="abrirModal('import')" 
                                        class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer bg-gradient-to-tl from-purple-700 to-pink-500 leading-pro text-sm ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs">
                                    <i class="fas fa-camera mr-2"></i>
                                    {{ isset($mascota) ? 'Cambiar imagen' : 'Seleccionar imagen' }}
                                </button>
                            </div>

                            <!-- Modal para imagen -->
                            <div class="fixed inset-0 hidden overflow-x-hidden overflow-y-auto z-50" id="import" aria-hidden="true">
                                <div class="flex items-center justify-center min-h-screen p-4">
                                    <div class="relative w-full max-w-md mx-auto bg-white rounded-xl shadow-xl">
                                        <div class="flex items-center justify-between p-4 border-b border-solid shrink-0 border-slate-100 rounded-t-xl">
                                            <h5 class="mb-0 leading-normal dark:text-white" id="ModalLabel">Importar Imagen</h5>
                                            <i class="ml-4 fas fa-upload"></i>
                                            <button type="button" onclick="cerrarModal('import')" class="fa fa-close w-4 h-4 ml-auto box-content p-2 text-black dark:text-white border-0 rounded-1.5 opacity-50 cursor-pointer -m-2 hover:opacity-100"></button>
                                        </div>
                                        <div class="relative flex-auto p-4">
                                            <p class="mb-4">Selecciona una fotografía de tu mascota.</p>
                                            <input type="file" name="imagen" id="archivo" accept=".jpg, .jpeg, .png"
                                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-tl file:from-purple-700 file:to-pink-500 file:text-white hover:file:opacity-80 dark:file:bg-gray-800 dark:file:text-white cursor-pointer" />

                                            <div class="min-h-6 pl-7 mb-0.5 block mt-4">
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

                            <!-- Nombre -->
                            <div class="mb-4">
                                <label for="nombre" class="block text-sm font-medium text-slate-700 mb-2">
                                    Nombre de la mascota *
                                </label>
                                <input type="text" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="{{ old('nombre', $mascota->nombre ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-slate-800 dark:border-slate-600 dark:text-white"
                                       placeholder="Nombre de tu mascota"
                                       required>
                            </div>

                            @auth
                                @if(auth()->user()->is_admin)
                                    <div class="mb-4">
                                        <label for="user_id" class="block text-sm font-medium text-slate-700 mb-2">
                                            ID Usuario
                                        </label>
                                        <input type="number" 
                                               id="user_id" 
                                               name="user_id" 
                                               value="{{ old('user_id', $mascota->user_id ?? '') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-slate-800 dark:border-slate-600 dark:text-white"
                                               placeholder="ID del usuario propietario">
                                    </div>
                                @else
                                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                                @endif
                            @endauth

                            <!-- Especie -->
                            <div class="mb-4">
                                <label for="especie" class="block text-sm font-medium text-slate-700 mb-2">
                                    Especie *
                                </label>
                                <div class="relative">
                                    <button type="button" id="dropdownEspecieBtn" onclick="toggleDropdownEspecie()"
                                            class="w-full px-3 py-2 text-left border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white dark:bg-slate-800 dark:border-slate-600 dark:text-white">
                                        <span id="dropdownEspecieText">{{ collect($especies)->firstWhere('value', old('especie', $mascota->especie ?? ''))['label'] ?? '-- Selecciona especie --' }}</span>
                                        <i class="fas fa-chevron-down float-right mt-1"></i>
                                    </button>

                                    <ul id="dropdownEspecieMenu"
                                        class="z-10 hidden absolute w-full mt-1 rounded-lg bg-white dark:bg-slate-800 shadow-lg border border-gray-200 dark:border-slate-600 text-sm text-slate-700 dark:text-white max-h-60 overflow-y-auto">
                                        @foreach ($especies as $especie)
                                            <li>
                                                <a href="javascript:void(0);"
                                                   onclick="seleccionarEspecie(event, '{{ $especie['value'] }}', '{{ $especie['label'] }}')"
                                                   class="block px-4 py-2 hover:bg-purple-50">
                                                    {{ $especie['label'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <input type="hidden" name="especie" id="especie" value="{{ old('especie', $mascota->especie ?? '') }}">
                                </div>
                            </div>

                            <!-- Raza -->
                            <div class="mb-4">
                                <label for="raza" class="block text-sm font-medium text-slate-700 mb-2">
                                    Raza
                                </label>
                                <div class="relative">
                                    <button id="dropdownRazaBtn" onclick="toggleDropdownRaza()" type="button"
                                            class="w-full px-3 py-2 text-left border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white dark:bg-slate-800 dark:border-slate-600 dark:text-white">
                                        <span id="dropdownRazaText">{{ old('raza') ?? '-- Selecciona raza --' }}</span>
                                        <i class="fas fa-chevron-down float-right mt-1"></i>
                                    </button>

                                    <ul id="dropdownRazaMenu"
                                        class="z-10 hidden absolute w-full mt-1 rounded-lg bg-white dark:bg-slate-800 shadow-lg border border-gray-200 dark:border-slate-600 text-sm text-slate-700 dark:text-white max-h-60 overflow-y-auto">
                                        <!-- Se rellenará dinámicamente -->
                                    </ul>
                                    <input type="hidden" name="raza" id="raza" value="{{ old('raza') }}">
                                </div>
                            </div>

                            <!-- Sexo -->
                            <div class="mb-4">
                                <label for="sexo" class="block text-sm font-medium text-slate-700 mb-2">
                                    Sexo
                                </label>
                                <div class="relative">
                                    <button id="dropdownSexoBtn" onclick="toggleDropdownSexo()" type="button"
                                            class="w-full px-3 py-2 text-left border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white dark:bg-slate-800 dark:border-slate-600 dark:text-white">
                                        <span id="dropdownSexoText">{{ old('sexo') ? ucfirst(old('sexo')) : '-- Selecciona sexo --' }}</span>
                                        <i class="fas fa-chevron-down float-right mt-1"></i>
                                    </button>

                                    <ul id="dropdownSexoMenu"
                                        class="z-10 hidden absolute w-full mt-1 rounded-lg bg-white dark:bg-slate-800 shadow-lg border border-gray-200 dark:border-slate-600 text-sm text-slate-700 dark:text-white max-h-60 overflow-y-auto">
                                        @foreach ($sexos as $sexo)
                                            <li>
                                                <a href="javascript:void(0);"
                                                   onclick="seleccionarSexo(event, '{{ $sexo->value }}', '{{ ucfirst($sexo->value) }}')"
                                                   class="block px-4 py-2 hover:bg-purple-50">
                                                    {{ ucfirst($sexo->value) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <input type="hidden" name="sexo" id="sexo" value="{{ old('sexo') }}">
                                </div>
                            </div>

                            <!-- Fecha de nacimiento -->
                            <div class="mb-4">
                                <label for="fecha_nacimiento" class="block text-sm font-medium text-slate-700 mb-2">
                                    Fecha de nacimiento
                                </label>
                                <input type="date" 
                                       id="fecha_nacimiento" 
                                       name="fecha_nacimiento" 
                                       value="{{ old('fecha_nacimiento', $mascota->fecha_nacimiento ?? '') }}"
                                       max="{{ date('Y-m-d') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-slate-800 dark:border-slate-600 dark:text-white">
                            </div>

                            <!-- Notas -->
                            <div class="mb-6">
                                <label for="notas" class="block text-sm font-medium text-slate-700 mb-2">
                                    Notas adicionales
                                </label>
                                <textarea id="notas" 
                                          name="notas" 
                                          rows="4"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-slate-800 dark:border-slate-600 dark:text-white"
                                          placeholder="Información adicional sobre tu mascota...">{{ old('notas', $mascota->notas ?? '') }}</textarea>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <a href="{{ session('return_to_after_update', route('usuarios.show', auth()->user())) }}"
                                   class="inline-block px-6 py-3 font-bold text-center bg-gray-500 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Volver
                                </a>
                                <button type="submit" 
                                        class="inline-block px-6 py-3 font-bold text-center bg-gradient-to-tl from-purple-700 to-pink-500 uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs text-white">
                                    <i class="fas fa-save mr-2"></i>
                                    {{ isset($mascota) ? 'Actualizar Mascota' : 'Guardar Mascota' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="w-full max-w-full px-3 mt-6 lg:mt-0 lg:w-4/12 lg:flex-none">
                <div class="relative flex flex-col h-full min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                        <h6 class="mb-0">Información Importante</h6>
                    </div>
                    
                    <div class="flex-auto p-4">
                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-image text-white text-sm"></i>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-slate-700">Foto</h6>
                                    <p class="text-sm text-slate-600">Sube una foto clara de tu mascota</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-info-circle text-white text-sm"></i>
                                </div>
                                <div>
                                    <h6 class="text-sm font-semibold text-slate-700">Información</h6>
                                    <p class="text-sm text-slate-600">Completa todos los campos obligatorios</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-tl from-purple-700 to-pink-500 rounded-lg p-4 text-white">
                            <h6 class="text-sm font-semibold mb-2">Consejo</h6>
                            <p class="text-sm opacity-90">Una vez registrada, podrás agregar recordatorios y seguimiento médico.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    // --- Dropdown Especie ---
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
        actualizarRazas(); // Llamada directa
    }

    // --- Dropdown Raza ---
    function toggleDropdownRaza() {
        const menu = document.getElementById('dropdownRazaMenu');
        menu.classList.toggle('hidden');
    }
    function seleccionarRaza(event, valor) {
        event.stopPropagation();
        event.preventDefault();
        document.getElementById('raza').value = valor;
        document.getElementById('dropdownRazaText').textContent = valor;
        document.getElementById('dropdownRazaMenu').classList.add('hidden');
    }
    // --- Dropdown Sexo ---
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

    // --- Actualizar Razas según especie seleccionada ---
    const razasPorEspecie = @json($razasPorEspecie);
    function actualizarRazas() {
        const especie = document.getElementById('especie').value;
        const razas = razasPorEspecie[especie] || [];
        const razaMenu = document.getElementById('dropdownRazaMenu');
        const razaInput = document.getElementById('raza');
        const razaText = document.getElementById('dropdownRazaText');
        const razaActual = razaInput.value;
        razaMenu.innerHTML = '';
        if (razas.length === 0) {
            razaText.textContent = '-- Selecciona raza --';
            razaInput.value = '';
            return;
        }
        let razaSeleccionada = false;
        razas.forEach(function(raza) {
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = 'javascript:void(0);';
            a.className = 'block px-4 py-2 hover:bg-purple-50';
            a.textContent = raza;
            a.onclick = function(event) { seleccionarRaza(event, raza); };
            if (raza === razaActual) {
                a.className += ' bg-purple-100 font-bold';
                razaSeleccionada = true;
            }
            li.appendChild(a);
            razaMenu.appendChild(li);
        });
        if (razaSeleccionada) {
            razaText.textContent = razaActual;
        } else {
            razaText.textContent = '-- Selecciona raza --';
            razaInput.value = '';
        }
    }

    // --- Inicialización al cargar la página ---
    document.addEventListener('DOMContentLoaded', function() {
        // Especie
        const especie = document.getElementById('especie').value;
        if (especie) {
            const especieLabel = (Object.values(@json($especies)).find(e => e.value === especie) || {}).label;
            if (especieLabel) {
                document.getElementById('dropdownEspecieText').textContent = especieLabel;
            }
        }
        // Sexo
        const sexo = document.getElementById('sexo').value;
        if (sexo) {
            document.getElementById('dropdownSexoText').textContent = sexo.charAt(0).toUpperCase() + sexo.slice(1);
        }
        // Razas
        actualizarRazas();
    });

    // Cierre al hacer clic fuera
    document.addEventListener('click', function (event) {
        const menu = document.getElementById('dropdownEspecieMenu');
        const btn = document.getElementById('dropdownEspecieBtn');

        if (!menu.contains(event.target) && !btn.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });

    // Cerrar modal con Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('import');
            if (modal && !modal.classList.contains('hidden')) {
                cerrarModal('import');
            }
        }
    });

    // Cerrar modal al hacer clic en el backdrop
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('import');
        if (modal && event.target === modal) {
            cerrarModal('import');
        }
    });

    function abrirModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('block');
            modal.style.opacity = '1';
            modal.setAttribute('aria-hidden', 'false');
            
            // Agregar backdrop si no existe
            let backdrop = document.getElementById('modal-backdrop');
            if (!backdrop) {
                backdrop = document.createElement('div');
                backdrop.id = 'modal-backdrop';
                backdrop.className = 'fixed inset-0 bg-black bg-opacity-50 z-40';
                backdrop.onclick = function() {
                    cerrarModal(id);
                };
                document.body.appendChild(backdrop);
            }
            backdrop.classList.remove('hidden');
            backdrop.classList.add('block');
        }
    }

    function cerrarModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('block');
            modal.classList.add('hidden');
            modal.style.opacity = '0';
            modal.setAttribute('aria-hidden', 'true');
            
            // Ocultar backdrop
            const backdrop = document.getElementById('modal-backdrop');
            if (backdrop) {
                backdrop.classList.remove('block');
                backdrop.classList.add('hidden');
            }
        }
    }
</script>
@endsection
