@extends('layouts.app')

@section('title', 'Mi Calendario - Veterinario')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<style>
    .fc-event {
        cursor: pointer;
    }
    .fc-event-pendiente {
        background-color: #6b7280 !important;
        border-color: #4b5563 !important;
    }
    .fc-event-realizado {
        background-color: #10b981 !important;
        border-color: #059669 !important;
    }
    .fc-event-no-completado {
        background-color: #ef4444 !important;
        border-color: #dc2626 !important;
    }
    .fc-event-cita {
        background-color: #f59e0b !important;
        border-color: #d97706 !important;
    }
    .fc-toolbar-title {
        color: #374151 !important;
    }
    .fc-button-primary {
        background-color: #3b82f6 !important;
        border-color: #2563eb !important;
    }
    .fc-button-primary:hover {
        background-color: #2563eb !important;
        border-color: #1d4ed8 !important;
    }
    
    /* Estilos para modo oscuro */
    .dark .fc-toolbar-title {
        color: #f1f5f9 !important;
    }
    .dark .fc-col-header-cell {
        background-color: #475569 !important;
        color: #f1f5f9 !important;
    }
    .dark .fc-daygrid-day {
        background-color: #334155 !important;
        color: #f1f5f9 !important;
    }
    .dark .fc-daygrid-day-number {
        color: #f1f5f9 !important;
    }
    .dark .fc-day-today {
        background-color: #475569 !important;
    }
</style>
@endpush

@section('content')
<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white dark:bg-slate-400 dark:text-white shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white dark:bg-slate-400 dark:text-white rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h6 class="text-lg font-semibold text-slate-700 dark:text-white">Mi Calendario -{{ $veterinario->nombre }}</h6>
                    <a href="{{ route('citas.create') }}"
                       class="h-10 px-6 py-2 font-bold text-center bg-gradient-to-tl from-purple-700 to-pink-500 uppercase rounded-lg text-xs text-white flex items-center justify-center">
                        Crear Cita
                    </a>
                </div>
                <!-- Leyenda de colores -->
                <div class="mt-4 flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-yellow-500 rounded"></div>
                        <span class="text-gray-700 dark:text-white">Citas pendientes</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-green-500 rounded"></div>
                        <span class="text-gray-700 dark:text-white">Citas realizadas</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-red-500 rounded"></div>
                        <span class="text-gray-700 dark:text-white">Citas no completadas</span>
                    </div>
                </div>
                <!-- Botón de exportación -->
                <div class="mt-4 flex flex-wrap gap-4 text-sm">
                    <button id="exportarCSV" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Exportar CSV</button>
                    <button id="exportarExcel" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">Exportar Excel</button>
                </div>
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-6">
                    <div class="relative z-0 flex flex-col min-w-0 p-3 break-words bg-white dark:bg-slate-400 dark:text-white border-0 shadow-xl rounded-2xl bg-clip-border">
                        <div data-toggle="calendar" id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar detalles del evento -->
<div id="eventoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-slate-400 dark:text-white rounded-lg shadow-xl max-w-md w-full">
            <div class="flex items-center justify-between p-4 border-b dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modalTitle"></h3>
                <button onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Cliente:</label>
                    <p id="modalCliente" class="text-sm text-gray-900 dark:text-white"></p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Mascota:</label>
                    <p id="modalMascota" class="text-sm text-gray-900 dark:text-white"></p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Descripción:</label>
                    <p id="modalDescripcion" class="text-sm text-gray-900 dark:text-white"></p>
                </div>
                <div class="mb-4" id="modalEstadoContainer">
                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Estado:</label>
                    <p id="modalEstado" class="text-sm font-semibold"></p>
                </div>
                <div class="mb-4" id="modalTipoContainer" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Tipo:</label>
                    <p id="modalTipo" class="text-sm text-gray-900 dark:text-white"></p>
                </div>
                <div class="flex justify-end space-x-2">
                    <button onclick="cerrarModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-white bg-gray-200 dark:bg-gray-600 rounded-md hover:bg-gray-300 dark:hover:bg-gray-500">
                        Cerrar
                    </button>
                    <button id="cambiarEstadoBtn" onclick="cambiarEstado()" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700" style="display: none;">
                        Cambiar Estado
                    </button>
                    <a id="modalEditar" href="#" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                        Editar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
// Declarar variables globales para los eventos
var eventosCitas = [];

// Función para determinar el estado de una cita basado en fecha y realizado
function determinarEstadoCita(fecha, realizado) {
    const fechaCita = new Date(fecha);
    const fechaActual = new Date();
    fechaActual.setHours(0, 0, 0, 0); // Resetear a inicio del día
    
    if (realizado) {
        return 'realizado';
    } else if (fechaCita < fechaActual) {
        return 'no-completado';
    } else {
        return 'pendiente';
    }
}

// Función para obtener la clase CSS según el estado
function obtenerClaseCSS(estado) {
    switch(estado) {
        case 'realizado':
            return 'fc-event-realizado';
        case 'no-completado':
            return 'fc-event-no-completado';
        case 'pendiente':
            return 'fc-event-pendiente';
        default:
            return 'fc-event-cita';
    }
}

// Función para obtener el texto del estado
function obtenerTextoEstado(estado) {
    switch(estado) {
        case 'realizado':
            return 'Realizada';
        case 'no-completado':
            return 'No completada';
        case 'pendiente':
            return 'Pendiente';
        default:
            return 'Pendiente';
    }
}

// Función para obtener los eventos actualmente visibles según los filtros
function obtenerEventosFiltrados() {
    return eventosCitas;
}

// Función para exportar a CSV
function exportarCSV() {
    var eventos = obtenerEventosFiltrados();
    if (eventos.length === 0) {
        alert('No hay datos para exportar.');
        return;
    }
    var csv = 'Título,Cliente,Mascota,Descripción,Fecha,Estado\n';
    eventos.forEach(function(ev) {
        const estado = determinarEstadoCita(ev.start, ev.extendedProps.realizado);
        const textoEstado = obtenerTextoEstado(estado);
        
        csv += '"' + ev.title.replace(/"/g, '""') + '"';
        csv += ',' + '"' + ev.extendedProps.cliente.replace(/"/g, '""') + '"';
        csv += ',' + '"' + ev.extendedProps.mascota.replace(/"/g, '""') + '"';
        csv += ',' + '"' + ev.extendedProps.descripcion.replace(/"/g, '""') + '"';
        csv += ',' + ev.start;
        csv += ',' + textoEstado;
        csv += '\n';
    });
    var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    var link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'citas_veterinario.csv';
    link.click();
}

// Función para exportar a Excel
function exportarExcel() {
    var eventos = obtenerEventosFiltrados();
    if (eventos.length === 0) {
        alert('No hay datos para exportar.');
        return;
    }
    var data = [
        ['Título', 'Cliente', 'Mascota', 'Descripción', 'Fecha', 'Estado']
    ];
    eventos.forEach(function(ev) {
        const estado = determinarEstadoCita(ev.start, ev.extendedProps.realizado);
        const textoEstado = obtenerTextoEstado(estado);
        
        data.push([
            ev.title,
            ev.extendedProps.cliente,
            ev.extendedProps.mascota,
            ev.extendedProps.descripcion,
            ev.start,
            textoEstado
        ]);
    });
    var ws = XLSX.utils.aoa_to_sheet(data);
    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Citas');
    XLSX.writeFile(wb, 'citas_veterinario.xlsx');
}

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    // Variables para almacenar los eventos por tipo
    eventosCitas = [
        @foreach($citas as $cita)
        {
            id: 'cita_{{ $cita->id }}',
            title: '{{ $cita->titulo }}',
            start: '{{ $cita->fecha->format('Y-m-d') }}',
            className: '{{ $cita->realizado ? 'fc-event-realizado' : ($cita->fecha->isPast() ? 'fc-event-no-completado' : 'fc-event-cita') }}',
            extendedProps: {
                tipo: 'cita',
                cliente: '{{ $cita->mascota->usuario->name }}',
                mascota: '{{ $cita->mascota->nombre }}',
                descripcion: '{{ $cita->descripcion ?? 'Sin descripción' }}',
                realizado: {{ $cita->realizado ? 'true' : 'false' }},
                editUrl: '{{ route('citas.edit', $cita->hashid) }}'
            }
        },
        @endforeach
    ];
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            list: 'Lista'
        },
        events: eventosCitas,
        eventClick: function(info) {
            mostrarModal(info.event);
        },
        eventDidMount: function(info) {
            // Agregar tooltip con información adicional
            info.el.title = `${info.event.title} - ${info.event.extendedProps.cliente} (Cita)`;
        }
    });
    
    calendar.render();
});

function mostrarModal(event) {
    const modal = document.getElementById('eventoModal');
    const title = document.getElementById('modalTitle');
    const cliente = document.getElementById('modalCliente');
    const mascota = document.getElementById('modalMascota');
    const descripcion = document.getElementById('modalDescripcion');
    const estado = document.getElementById('modalEstado');
    const estadoContainer = document.getElementById('modalEstadoContainer');
    const editLink = document.getElementById('modalEditar');
    const cambiarEstadoBtn = document.getElementById('cambiarEstadoBtn');
    
    // Almacenar el evento actual
    currentEvent = event;
    
    title.textContent = event.title;
    cliente.textContent = event.extendedProps.cliente;
    mascota.textContent = event.extendedProps.mascota;
    descripcion.textContent = event.extendedProps.descripcion;
    
    // Mostrar información de cita
    estadoContainer.style.display = 'block';
    
    const estadoCita = determinarEstadoCita(event.start, event.extendedProps.realizado);
    const textoEstado = obtenerTextoEstado(estadoCita);
    
    estado.textContent = textoEstado;
    
    // Aplicar color según el estado
    switch(estadoCita) {
        case 'realizado':
            estado.className = 'text-sm font-semibold text-green-600';
            break;
        case 'no-completado':
            estado.className = 'text-sm font-semibold text-red-600';
            break;
        case 'pendiente':
            estado.className = 'text-sm font-semibold text-gray-600';
            break;
        default:
            estado.className = 'text-sm font-semibold text-gray-600';
    }
    
    editLink.href = event.extendedProps.editUrl;
    
    // Mostrar botón de cambiar estado solo para citas
    if (event.extendedProps.tipo === 'cita') {
        cambiarEstadoBtn.style.display = 'inline-block';
        cambiarEstadoBtn.textContent = event.extendedProps.realizado ? 'Marcar como Pendiente' : 'Marcar como Realizada';
    } else {
        cambiarEstadoBtn.style.display = 'none';
    }
    
    modal.classList.remove('hidden');
}

function cerrarModal() {
    const modal = document.getElementById('eventoModal');
    modal.classList.add('hidden');
}

// Función para cambiar el estado de una cita
function cambiarEstado() {
    // Extraer el hashid de la URL de edición (formato: /recordatorios/{hashid}/edit)
    const editUrl = currentEvent.extendedProps.editUrl;
    const urlParts = editUrl.split('/');
    const eventId = urlParts[urlParts.length - 2]; // El hashid está antes del último segmento 'edit'
    const nuevoEstado = !currentEvent.extendedProps.realizado;
    
    console.log('Debug cambiarEstado:', {
        editUrl: editUrl,
        urlParts: urlParts,
        eventId: eventId,
        currentEstado: currentEvent.extendedProps.realizado,
        nuevoEstado: nuevoEstado
    });
    
    // Crear el formulario para enviar la petición
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/recordatorios/${eventId}/cambiar-estado`;
    
    // Agregar el token CSRF
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    form.appendChild(csrfToken);
    
    // Agregar el método PATCH
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'PATCH';
    form.appendChild(methodInput);
    
    // Agregar el nuevo estado
    const estadoInput = document.createElement('input');
    estadoInput.type = 'hidden';
    estadoInput.name = 'nuevo_estado';
    estadoInput.value = nuevoEstado ? '1' : '0';
    form.appendChild(estadoInput);
    
    console.log('Formulario creado:', {
        action: form.action,
        method: form.method,
        nuevo_estado: estadoInput.value
    });
    
    // Agregar el formulario al DOM y enviarlo
    document.body.appendChild(form);
    form.submit();
}

// Variable global para almacenar el evento actual
var currentEvent = null;

// Cerrar modal al hacer clic fuera de él
document.getElementById('eventoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModal();
    }
});

document.getElementById('exportarCSV').addEventListener('click', exportarCSV);
document.getElementById('exportarExcel').addEventListener('click', exportarExcel);
</script>
@endpush 