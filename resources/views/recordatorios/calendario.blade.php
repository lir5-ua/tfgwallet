@extends('layouts.app')

@section('title', 'Calendario de Recordatorios')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<style>
    .fc-event {
        cursor: pointer;
    }
    .fc-event-pendiente {
        background-color: #ef4444 !important;
        border-color: #dc2626 !important;
    }
    .fc-event-realizado {
        background-color: #10b981 !important;
        border-color: #059669 !important;
    }
    .fc-event-historial {
        background-color: #3b82f6 !important;
        border-color: #2563eb !important;
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
                    <h6 class="text-lg font-semibold text-slate-700 dark:text-white">Calendario de Recordatorios</h6>
                    <a href="{{ route('recordatorios.create', ['usuario_id' => $usuario->id]) }}"
                       class="h-10 px-6 py-2 font-bold text-center bg-gradient-to-tl from-green-600 to-lime-400 uppercase rounded-lg text-xs text-white flex items-center justify-center">
                        Crear Recordatorio
                    </a>
                </div>
                <!-- Filtros de eventos -->
                <div class="mt-4 flex flex-wrap gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="mostrarRecordatorios" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="mostrarRecordatorios" class="text-gray-700 dark:text-white font-medium">Mostrar Recordatorios</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="mostrarHistorial" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="mostrarHistorial" class="text-gray-700 dark:text-white font-medium">Mostrar Historial Médico</label>
                    </div>
                </div>
                <!-- Leyenda de colores -->
                <div class="mt-4 flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-red-500 rounded"></div>
                        <span class="text-gray-700 dark:text-white">Recordatorios pendientes</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-green-500 rounded"></div>
                        <span class="text-gray-700 dark:text-white">Recordatorios completados</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-blue-500 rounded"></div>
                        <span class="text-gray-700 dark:text-white">Historial médico</span>
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

<!-- Modal para mostrar detalles del recordatorio -->
<div id="recordatorioModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
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
                <div class="mb-4" id="modalVeterinarioContainer" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 dark:text-white">Veterinario:</label>
                    <p id="modalVeterinario" class="text-sm text-gray-900 dark:text-white"></p>
                </div>
                <div class="flex justify-end space-x-2">
                    <button onclick="cerrarModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-white bg-gray-200 dark:bg-gray-600 rounded-md hover:bg-gray-300 dark:hover:bg-gray-500">
                        Cerrar
                    </button>
                    <a id="modalEditar" href="#" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
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
var eventosRecordatorios = [];
var eventosHistorial = [];

// Función para obtener los eventos actualmente visibles según los filtros
function obtenerEventosFiltrados() {
    var mostrarRecordatorios = document.getElementById('mostrarRecordatorios').checked;
    var mostrarHistorial = document.getElementById('mostrarHistorial').checked;
    var eventos = [];
    if (mostrarRecordatorios) {
        eventos = eventos.concat(eventosRecordatorios);
    }
    if (mostrarHistorial) {
        eventos = eventos.concat(eventosHistorial);
    }
    return eventos;
}

// Función para exportar a CSV
function exportarCSV() {
    var eventos = obtenerEventosFiltrados();
    if (eventos.length === 0) {
        alert('No hay datos para exportar.');
        return;
    }
    var csv = 'Tipo,Título,Mascota,Descripción,Fecha,Estado/Tipo, Veterinario\n';
    eventos.forEach(function(ev) {
        if (ev.extendedProps.tipo === 'recordatorio') {
            csv += 'Recordatorio',
            csv += ',' + '"' + ev.title.replace(/"/g, '""') + '"';
            csv += ',' + '"' + ev.extendedProps.mascota.replace(/"/g, '""') + '"';
            csv += ',' + '"' + ev.extendedProps.descripcion.replace(/"/g, '""') + '"';
            csv += ',' + ev.start;
            csv += ',' + (ev.extendedProps.realizado ? 'Realizado' : 'Pendiente');
            csv += ',' + '';
        } else {
            csv += 'Historial Médico',
            csv += ',' + '"' + ev.title.replace(/"/g, '""') + '"';
            csv += ',' + '"' + ev.extendedProps.mascota.replace(/"/g, '""') + '"';
            csv += ',' + '"' + ev.extendedProps.descripcion.replace(/"/g, '""') + '"';
            csv += ',' + ev.start;
            csv += ',' + ev.extendedProps.tipoHistorial;
            csv += ',' + '"' + (ev.extendedProps.veterinario ? ev.extendedProps.veterinario.replace(/"/g, '""') : '') + '"';
        }
        csv += '\n';
    });
    var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    var link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'eventos_calendario.csv';
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
        ['Tipo', 'Título', 'Mascota', 'Descripción', 'Fecha', 'Estado/Tipo', 'Veterinario']
    ];
    eventos.forEach(function(ev) {
        if (ev.extendedProps.tipo === 'recordatorio') {
            data.push([
                'Recordatorio',
                ev.title,
                ev.extendedProps.mascota,
                ev.extendedProps.descripcion,
                ev.start,
                ev.extendedProps.realizado ? 'Realizado' : 'Pendiente',
                ''
            ]);
        } else {
            data.push([
                'Historial Médico',
                ev.title,
                ev.extendedProps.mascota,
                ev.extendedProps.descripcion,
                ev.start,
                ev.extendedProps.tipoHistorial,
                ev.extendedProps.veterinario || ''
            ]);
        }
    });
    var ws = XLSX.utils.aoa_to_sheet(data);
    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Eventos');
    XLSX.writeFile(wb, 'eventos_calendario.xlsx');
}

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    // Variables para almacenar los eventos por tipo
    eventosRecordatorios = [
        @foreach($recordatorios as $recordatorio)
        {
            id: 'recordatorio_{{ $recordatorio->id }}',
            title: '{{ $recordatorio->titulo }}',
            start: '{{ $recordatorio->fecha->format('Y-m-d') }}',
            className: '{{ $recordatorio->realizado ? 'fc-event-realizado' : 'fc-event-pendiente' }}',
            extendedProps: {
                tipo: 'recordatorio',
                mascota: '{{ $recordatorio->mascota->nombre }}',
                descripcion: '{{ $recordatorio->descripcion ?? 'Sin descripción' }}',
                realizado: {{ $recordatorio->realizado ? 'true' : 'false' }},
                editUrl: '{{ route('recordatorios.edit', $recordatorio) }}'
            }
        },
        @endforeach
    ];
    
    eventosHistorial = [
        @foreach($historialMedico as $historial)
        {
            id: 'historial_{{ $historial->id }}',
            title: '{{ $historial->tipo }} - {{ $historial->mascota->nombre }}',
            start: '{{ $historial->fecha->format('Y-m-d') }}',
            className: 'fc-event-historial',
            extendedProps: {
                tipo: 'historial',
                mascota: '{{ $historial->mascota->nombre }}',
                descripcion: '{{ $historial->descripcion ?? 'Sin descripción' }}',
                tipoHistorial: '{{ $historial->tipo }}',
                veterinario: '{{ $historial->veterinario ?? 'No especificado' }}',
                editUrl: '{{ route('mascotas.historial.edit', ['mascota' => $historial->mascota_id, 'historial' => $historial->id]) }}'
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
        events: [...eventosRecordatorios, ...eventosHistorial],
        eventClick: function(info) {
            mostrarModal(info.event);
        },
        eventDidMount: function(info) {
            // Agregar tooltip con información adicional
            if (info.event.extendedProps.tipo === 'recordatorio') {
                info.el.title = `${info.event.title} - ${info.event.extendedProps.mascota} (Recordatorio)`;
            } else {
                info.el.title = `${info.event.title} - ${info.event.extendedProps.mascota} (Historial Médico)`;
            }
        }
    });
    
    calendar.render();
    
    // Funciones para filtrar eventos
    function actualizarEventos() {
        var mostrarRecordatorios = document.getElementById('mostrarRecordatorios').checked;
        var mostrarHistorial = document.getElementById('mostrarHistorial').checked;
        
        // Remover todos los eventos
        calendar.removeAllEvents();
        
        // Agregar eventos según los filtros
        if (mostrarRecordatorios) {
            calendar.addEventSource(eventosRecordatorios);
        }
        if (mostrarHistorial) {
            calendar.addEventSource(eventosHistorial);
        }
    }
    
    // Event listeners para los checkboxes
    document.getElementById('mostrarRecordatorios').addEventListener('change', actualizarEventos);
    document.getElementById('mostrarHistorial').addEventListener('change', actualizarEventos);
});

function mostrarModal(event) {
    const modal = document.getElementById('recordatorioModal');
    const title = document.getElementById('modalTitle');
    const mascota = document.getElementById('modalMascota');
    const descripcion = document.getElementById('modalDescripcion');
    const estado = document.getElementById('modalEstado');
    const estadoContainer = document.getElementById('modalEstadoContainer');
    const tipo = document.getElementById('modalTipo');
    const tipoContainer = document.getElementById('modalTipoContainer');
    const veterinario = document.getElementById('modalVeterinario');
    const veterinarioContainer = document.getElementById('modalVeterinarioContainer');
    const editLink = document.getElementById('modalEditar');
    
    title.textContent = event.title;
    mascota.textContent = event.extendedProps.mascota;
    descripcion.textContent = event.extendedProps.descripcion;
    
    if (event.extendedProps.tipo === 'recordatorio') {
        // Mostrar información de recordatorio
        estadoContainer.style.display = 'block';
        tipoContainer.style.display = 'none';
        veterinarioContainer.style.display = 'none';
        
        if (event.extendedProps.realizado) {
            estado.textContent = 'Realizado';
            estado.className = 'text-sm font-semibold text-green-600';
        } else {
            estado.textContent = 'Pendiente';
            estado.className = 'text-sm font-semibold text-red-600';
        }
    } else {
        // Mostrar información de historial médico
        estadoContainer.style.display = 'none';
        tipoContainer.style.display = 'block';
        veterinarioContainer.style.display = 'block';
        
        tipo.textContent = event.extendedProps.tipoHistorial;
        veterinario.textContent = event.extendedProps.veterinario;
    }
    
    editLink.href = event.extendedProps.editUrl;
    
    modal.classList.remove('hidden');
}

function cerrarModal() {
    const modal = document.getElementById('recordatorioModal');
    modal.classList.add('hidden');
}

// Cerrar modal al hacer clic fuera de él
document.getElementById('recordatorioModal').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModal();
    }
});

document.getElementById('exportarCSV').addEventListener('click', exportarCSV);
document.getElementById('exportarExcel').addEventListener('click', exportarExcel);
</script>
@endpush 