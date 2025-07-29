@extends('layouts.app')

@section('title', 'Crear Cita')

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
                        <svg class="w-full h-full text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-none w-auto max-w-full px-3 my-auto">
                    <div class="h-full">
                        <h5 class="mb-1">Crear Nueva Cita</h5>
                        <p class="mb-0 text-sm text-slate-600">Programa una cita veterinaria para tus clientes</p>
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
                        <h6 class="mb-0">Información de la Cita</h6>
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

                        <form action="{{ route('citas.store') }}" method="POST" id="citaForm">
    @csrf

    <input type="hidden" id="final_usuario_id" name="usuario_id" value="{{ old('usuario_id') }}">
    <input type="hidden" id="final_mascota_id" name="mascota_id" value="{{ old('mascota_id') }}">
    
    <div class="mb-6">
        <h6 class="text-sm font-semibold text-slate-700 mb-4">Detalles de la Cita</h6>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="titulo" class="block text-sm font-medium text-slate-700 mb-2">
                    Título de la cita *
                </label>
                <input type="text" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white" 
                       id="titulo" 
                       name="titulo" 
                       value="{{ old('titulo') }}" 
                       placeholder="Ej: Revisión anual, Vacunación, etc."
                       required>
            </div>
            <div>
                <label for="fecha" class="block text-sm font-medium text-slate-700 mb-2">
                    Fecha y hora de la cita *
                </label>
                <input type="datetime-local" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white" 
                       id="fecha" 
                       name="fecha" 
                       value="{{ old('fecha') }}" 
                       min="{{ now()->format('Y-m-d\TH:i') }}"
                       required>
            </div>
        </div>

        <div class="mt-4">
            <label for="descripcion" class="block text-sm font-medium text-slate-700 mb-2">
                Descripción
            </label>
            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white" 
                      id="descripcion" 
                      name="descripcion" 
                      rows="3" 
                      placeholder="Detalles adicionales de la cita...">{{ old('descripcion') }}</textarea>
        </div>
    </div>

    <div class="mb-6">
        <h6 class="text-sm font-semibold text-slate-700 mb-4">Selección de Cliente</h6>
        
        <div class="space-y-3">
            <div class="flex items-center">
                <input class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500" 
                       type="radio" 
                       name="tipo_cliente" 
                       id="nuevo_cliente" 
                       value="nuevo" 
                       {{ old('tipo_cliente', 'nuevo') === 'nuevo' ? 'checked' : '' }}> {{-- Default to 'nuevo' or old value --}}
                <label class="ml-2 text-sm font-medium text-slate-700" for="nuevo_cliente">
                    Nuevo cliente
                </label>
            </div>
            <div class="flex items-center">
                <input class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500" 
                       type="radio" 
                       name="tipo_cliente" 
                       id="cliente_existente" 
                       value="existente" 
                       {{ old('tipo_cliente') === 'existente' ? 'checked' : '' }}>
                <label class="ml-2 text-sm font-medium text-slate-700" for="cliente_existente">
                    Cliente existente
                </label>
            </div>
        </div>
    </div>

    <div id="nuevo_cliente_section" class="mb-6" style="display: {{ old('tipo_cliente', 'nuevo') === 'nuevo' ? 'block' : 'none' }};">
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <h6 class="text-sm font-semibold text-purple-800 mb-3">Nuevo Cliente</h6>
            
            <div class="mb-4">
                <label for="codigo_mascota" class="block text-sm font-medium text-slate-700 mb-2">
                    Código de la mascota *
                </label>
                <div class="flex gap-2">
                    <input type="text" 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white" 
                           id="codigo_mascota" 
                           name="codigo_mascota" {{-- This field remains with its original name --}}
                           value="{{ old('codigo_mascota') }}" 
                           placeholder="Ingrese el código compartido por el cliente">
                    <button type="button" 
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors" 
                            id="buscar_codigo">
                        <i class="fas fa-search mr-1"></i>
                        Buscar
                    </button>
                </div>
                <p class="text-xs text-slate-600 mt-1">El cliente debe haber compartido el código de su mascota previamente.</p>
            </div>
            
            <div id="info_mascota" class="bg-white border border-purple-300 rounded-lg p-4" style="display: none;">
                <h6 class="text-sm font-semibold text-purple-800 mb-2">Información encontrada:</h6>
                <div class="space-y-2">
                    <p><span class="font-medium text-slate-700">Mascota:</span> <span id="nombre_mascota" class="text-purple-600"></span></p>
                    <p><span class="font-medium text-slate-700">Cliente:</span> <span id="nombre_cliente" class="text-purple-600"></span></p>
                    <p><span class="font-medium text-slate-700">Email:</span> <span id="email_cliente" class="text-purple-600"></span></p>
                </div>
            </div>
        </div>
    </div>

    <div id="cliente_existente_section" class="mb-6" style="display: {{ old('tipo_cliente') === 'existente' ? 'block' : 'none' }};">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h6 class="text-sm font-semibold text-blue-800 mb-3">Cliente Existente</h6>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="select_usuario_existente" class="block text-sm font-medium text-slate-700 mb-2">
                        Seleccionar cliente *
                    </label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white" 
                            id="select_usuario_existente"> {{-- Changed name to avoid conflict, JS will handle values --}}
                        <option value="">Seleccione un cliente...</option>
                        @foreach($usuariosExistentes as $usuario)
                            <option value="{{ $usuario->id }}" {{ old('usuario_id') == $usuario->id ? 'selected' : '' }}>
                                {{ $usuario->name }} ({{ $usuario->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="select_mascota_existente" class="block text-sm font-medium text-slate-700 mb-2">
                        Seleccionar mascota *
                    </label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white" 
                            id="select_mascota_existente"> {{-- Changed name to avoid conflict, JS will handle values --}}
                        <option value="">Primero seleccione un cliente...</option>
                        {{-- Pets for existing user will be loaded via JS --}}
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        <a href="{{ route('veterinarios.perfil', auth()->guard('veterinarios')->user()->hashid) }}" 
           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
            Cancelar
        </a>
        <button type="submit" 
                class="px-6 py-2 bg-gradient-to-tl from-purple-700 to-pink-500 text-white rounded-lg hover:from-purple-800 hover:to-pink-600 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-200">
            <i class="fas fa-calendar-plus mr-2"></i>
            Crear Cita
        </button>
    </div>
</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nuevoClienteRadio = document.getElementById('nuevo_cliente');
        const clienteExistenteRadio = document.getElementById('cliente_existente');
        const nuevoClienteSection = document.getElementById('nuevo_cliente_section');
        const clienteExistenteSection = document.getElementById('cliente_existente_section');

        const codigoMascotaInput = document.getElementById('codigo_mascota');
        const buscarCodigoBtn = document.getElementById('buscar_codigo');
        const infoMascotaDiv = document.getElementById('info_mascota');
        const nombreMascotaSpan = document.getElementById('nombre_mascota');
        const nombreClienteSpan = document.getElementById('nombre_cliente');
        const emailClienteSpan = document.getElementById('email_cliente');

        const finalUsuarioIdInput = document.getElementById('final_usuario_id');
        const finalMascotaIdInput = document.getElementById('final_mascota_id');

        const selectUsuarioExistente = document.getElementById('select_usuario_existente');
        const selectMascotaExistente = document.getElementById('select_mascota_existente');

        // Function to toggle sections
        function toggleClientSections() {
            if (nuevoClienteRadio.checked) {
                nuevoClienteSection.style.display = 'block';
                clienteExistenteSection.style.display = 'none';
                // Clear existing client selected values and disable them for submission
                selectUsuarioExistente.value = '';
                selectMascotaExistente.innerHTML = '<option value="">Primero seleccione un cliente...</option>';
                selectMascotaExistente.disabled = true; // Disable to prevent submission
            } else {
                nuevoClienteSection.style.display = 'none';
                clienteExistenteSection.style.display = 'block';
                // Clear new client info and hidden fields for submission
                infoMascotaDiv.style.display = 'none';
                nombreMascotaSpan.textContent = '';
                nombreClienteSpan.textContent = '';
                emailClienteSpan.textContent = '';
                codigoMascotaInput.value = '';
                selectMascotaExistente.disabled = false; // Enable for submission
            }
            // Clear the hidden fields that will be submitted whenever the type changes
            finalUsuarioIdInput.value = '';
            finalMascotaIdInput.value = '';
        }

        // Initial call to set correct section visibility based on old input or default
        toggleClientSections();

        // Event listeners for radio buttons
        nuevoClienteRadio.addEventListener('change', toggleClientSections);
        clienteExistenteRadio.addEventListener('change', toggleClientSections);

        // --- Logic for New Client Section ---
        
        // Función para buscar mascota por código
        function buscarMascotaPorCodigo() {
            const codigo = codigoMascotaInput.value.trim();
            if (codigo) {
                console.log('Buscando código:', codigo); // Debug
                console.log('CSRF Token:', '{{ csrf_token() }}'); // Debug
                
                fetch('{{ route("citas.buscar-mascota") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ codigo: codigo })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Respuesta del servidor:', data); // Debug completo
                    if (data.success) {
                        nombreMascotaSpan.textContent = data.mascota.nombre;
                        nombreClienteSpan.textContent = data.mascota.usuario.name;
                        emailClienteSpan.textContent = data.mascota.usuario.email;
                        infoMascotaDiv.style.display = 'block';
                        
                        // Set the values for the hidden input fields that will be submitted
                        finalUsuarioIdInput.value = data.mascota.usuario.id;
                        finalMascotaIdInput.value = data.mascota.id;
                        
                        // Debug: mostrar los valores en consola
                        console.log('Usuario ID establecido:', finalUsuarioIdInput.value);
                        console.log('Mascota ID establecido:', finalMascotaIdInput.value);
                        console.log('Datos completos de la mascota:', data.mascota);

                        alert('Mascota y cliente encontrados correctamente.');
                    } else {
                        infoMascotaDiv.style.display = 'none';
                        alert(data.message || 'Error al buscar la mascota.');
                        // Clear hidden fields if search fails
                        finalUsuarioIdInput.value = '';
                        finalMascotaIdInput.value = '';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al conectar con el servidor.');
                    infoMascotaDiv.style.display = 'none';
                    // Clear hidden fields on error
                    finalUsuarioIdInput.value = '';
                    finalMascotaIdInput.value = '';
                });
            } else {
                alert('Por favor, ingrese un código de mascota.');
            }
        }

        buscarCodigoBtn.addEventListener('click', buscarMascotaPorCodigo);

        // Auto-búsqueda cuando se pega el código
        codigoMascotaInput.addEventListener('paste', function(e) {
            // Pequeño delay para asegurar que el valor se haya pegado
            setTimeout(buscarMascotaPorCodigo, 100);
        });

        // Auto-búsqueda cuando se presiona Enter en el campo
        codigoMascotaInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                buscarMascotaPorCodigo();
            }
        });

        // --- Logic for Existing Client Section ---
        selectUsuarioExistente.addEventListener('change', function () {
            const userId = this.value;
            selectMascotaExistente.innerHTML = '<option value="">Cargando mascotas...</option>';
            selectMascotaExistente.disabled = true;
            finalMascotaIdInput.value = ''; // Clear final mascota_id when user changes

            if (userId) {
                // Set the final usuario_id when an existing user is selected
                finalUsuarioIdInput.value = userId; 

                fetch('{{ route("citas.obtener-mascotas") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ usuario_id: userId })
                })
                .then(response => response.json())
                .then(data => {
                    selectMascotaExistente.innerHTML = '<option value="">Seleccione una mascota...</option>';
                    if (data.success && data.mascotas.length > 0) {
                        data.mascotas.forEach(mascota => {
                            const option = document.createElement('option');
                            option.value = mascota.id;
                            option.textContent = `${mascota.nombre} (${mascota.especie})`;
                            selectMascotaExistente.appendChild(option);
                        });
                        selectMascotaExistente.disabled = false;
                        // If old('mascota_id') exists, try to pre-select it
                        @if(old('mascota_id') && old('usuario_id') == $usuario->id)
                            selectMascotaExistente.value = "{{ old('mascota_id') }}";
                            finalMascotaIdInput.value = "{{ old('mascota_id') }}"; // Set final ID if pre-selected
                        @endif
                    } else {
                        selectMascotaExistente.innerHTML = '<option value="">No se encontraron mascotas para este cliente.</option>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    selectMascotaExistente.innerHTML = '<option value="">Error al cargar mascotas.</option>';
                });
            } else {
                selectMascotaExistente.innerHTML = '<option value="">Primero seleccione un cliente...</option>';
                selectMascotaExistente.disabled = true;
                finalUsuarioIdInput.value = ''; // Clear final usuario_id if no user is selected
            }
        });

        // Event listener for existing pet selection to update the final_mascota_id
        selectMascotaExistente.addEventListener('change', function() {
            finalMascotaIdInput.value = this.value;
        });

        // Initialize mascota dropdown for existing client if a user was previously selected (e.g., after validation error)
        @if(old('usuario_id') && old('tipo_cliente') === 'existente')
            // Trigger change event on load if old usuario_id exists
            selectUsuarioExistente.dispatchEvent(new Event('change'));
        @endif

        // Validación adicional antes del envío del formulario
        document.getElementById('citaForm').addEventListener('submit', function(e) {
            const tipoCliente = document.querySelector('input[name="tipo_cliente"]:checked').value;
            
            if (tipoCliente === 'nuevo') {
                if (!finalUsuarioIdInput.value || !finalMascotaIdInput.value) {
                    e.preventDefault();
                    alert('Por favor, busque y seleccione una mascota válida antes de crear la cita.');
                    return false;
                }
            } else {
                if (!finalUsuarioIdInput.value || !finalMascotaIdInput.value) {
                    e.preventDefault();
                    alert('Por favor, seleccione un cliente y una mascota antes de crear la cita.');
                    return false;
                }
            }
            
            // Debug: mostrar los valores que se van a enviar
            console.log('Enviando formulario con:');
            console.log('Usuario ID:', finalUsuarioIdInput.value);
            console.log('Mascota ID:', finalMascotaIdInput.value);
            console.log('Tipo cliente:', tipoCliente);
        });
    });
</script>
@endpush
@endsection 