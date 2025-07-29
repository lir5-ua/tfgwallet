<?php

namespace App\Http\Controllers;
use App\Models\Mascota;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Recordatorio;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Vinkla\Hashids\Facades\Hashids;

class RecordatorioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $usuario = auth()->user();
        if ($usuario->is_admin) {
            // Admin ve todos los recordatorios de todas las mascotas
            $query = Recordatorio::with('mascota');
        } else {
            $mascotaIds = $usuario->mascotas->pluck('id');
            $query = Recordatorio::whereIn('mascota_id', $mascotaIds)
                ->with('mascota');
        }

        if ($request->filled('mascota')) {
            $query->where('mascota_id', $request->mascota);
        }

        if ($request->filled('titulo')) {
            $query->where('titulo', 'like', '%' . $request->titulo . '%');
        }
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', '>=', $request->fecha);
        }
        if ($request->filled('estado')) {
            $query->where('realizado', $request->estado);
        }

        // Solo aplicar el filtro por usuario si se ha enviado usuario_id
        if ($usuario->is_admin && $request->filled('usuario_id')) {
            $query->whereHas('mascota', function ($q) use ($request) {
                $q->where('user_id', $request->usuario_id);
            });
        }

        $recordatorios = $query->orderBy('realizado')->orderBy('fecha')->paginate(10)->appends($request->except('page'));

        // Si el admin filtra por usuario, solo mostrar mascotas de ese usuario
        if ($usuario->is_admin) {
            $mascotasUnicas = Mascota::orderBy('nombre')->get(['id', 'nombre']);
        } else {
            $mascotasUnicas = $usuario->mascotas()->orderBy('nombre')->get(['id', 'nombre']);
        }

        $hoy = Carbon::today();
        $manana = now()->addDay()->toDateString();
        $pasado = now()->addDays(2)->toDateString();

        return view('recordatorios.personales', compact('recordatorios', 'usuario', 'mascotasUnicas', 'hoy', 'manana', 'pasado'));
    }

    protected function resolveMascota($hashid)
    {
        
        $ids = Hashids::decode($hashid);
        if (count($ids) === 0) {
            abort(404);
        }
        return \App\Models\Mascota::findOrFail($ids[0]);
    }

    protected function resolveRecordatorio($hashid)
    {
        $ids = Hashids::decode($hashid);
        if (count($ids) === 0) {
            abort(404);
        }
        return \App\Models\Recordatorio::findOrFail($ids[0]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $hashid = null)
    {
        $mascota = $hashid ? $this->resolveMascota($hashid) : null;
        if ($mascota) {
            $mascotas = collect([$mascota]);
        } elseif ($request->has('mascota_id')) {
            $mascota = $this->resolveMascota($request->mascota_id);
            $mascotas = collect([$mascota]);
        } else {
            $usuarioId = $request->get('usuario_id');
            $usuario = $usuarioId ? User::findOrFail($usuarioId) : auth()->user();
            $mascotas = $usuario->mascotas;
            $mascota = null;
        }
        return view('recordatorios.create', compact('mascotas', 'mascota'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $hashid = null)
    {
        $mascota = $hashid ? $this->resolveMascota($hashid) : null;
        $validated = $request->validate([
            'mascota_id' => 'required',
            'titulo' => 'required|string|max:255',
            'fecha' => 'required|date',
            'descripcion' => 'nullable|string',
        ]);
        $mascota = $mascota ?: $this->resolveMascota($validated['mascota_id']);
        $recordatorio = $mascota->recordatorios()->create([
            'titulo' => $validated['titulo'],
            'fecha' => $validated['fecha'],
            'descripcion' => $validated['descripcion'],
            'realizado' => false,
        ]);
        return redirect()->route('recordatorios.index')->with('success', 'Recordatorio creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($hashid)
    {
        $recordatorio = $this->resolveRecordatorio($hashid);
        $authUser = auth()->user();
        if ($authUser->id !== $recordatorio->mascota->user_id && !$authUser->is_admin) {
            abort(403, 'No tienes permiso para acceder a este recordatorio.');
        }
        $referer = url()->previous();
        if (!str_contains($referer, '/edit')) {
            session(['return_to_after_update' => $referer]);
        }
        return view('recordatorios.show', compact('recordatorio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($hashid)
    {
        $recordatorio = $this->resolveRecordatorio($hashid);
        $authUser = auth()->user();
        if ($authUser->id !== $recordatorio->mascota->user_id && !$authUser->is_admin) {
            abort(403, 'No tienes permiso para acceder a este recordatorio.');
        }
        return view('recordatorios.edit', compact('recordatorio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $hashid)
    {
        $recordatorio = $this->resolveRecordatorio($hashid);
        $authUser = auth()->user();
        if ($authUser->id !== $recordatorio->mascota->user_id && !$authUser->is_admin) {
            abort(403, 'No tienes permiso para acceder a este recordatorio.');
        }
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'realizado' => 'nullable|boolean',
        ]);
        $updateData = [
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
        ];
        if ($recordatorio->fecha->isToday() || $recordatorio->fecha->isFuture()) {
            $updateData['realizado'] = $request->has('realizado');
        }
        $recordatorio->update($updateData);
        $usuarioId = $recordatorio->mascota->user_id;
        $cacheKey = "recordatorios_profile_user_{$usuarioId}";
        Cache::forget($cacheKey);
        return redirect(session()->pull('return_to_after_update', route('usuarios.show', auth()->user())))
            ->with('success', 'Recordatorio actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hashid)
    {
        $recordatorio = $this->resolveRecordatorio($hashid);
        $authUser = auth()->user();
        if ($authUser->id !== $recordatorio->mascota->user_id && !$authUser->is_admin) {
            abort(403, 'No tienes permiso para acceder a este recordatorio.');
        }
        $usuario = $recordatorio->mascota->usuario;
        $recordatorio->delete();
        return redirect()->back()->with('success', 'Entrada historial eliminada');
    }

    public function marcarComoVisto($hashid)
    {
        $recordatorio = $this->resolveRecordatorio($hashid);
        $recordatorio->realizado = true;
        $recordatorio->save();

        $usuarioId = $recordatorio->mascota->user_id;

        $cacheKey = "recordatorios_profile_user_{$usuarioId}";

        Cache::forget($cacheKey);
        return back()->with('success', 'Estado actualizado');
    }

    public function mascota()
    {
        return $this->belongsTo(Mascota::class);
    }

    public function personales(User $usuario, Request $request)
    {
        $authUser = auth()->user();
        if ($authUser->id !== $usuario->id && !$authUser->is_admin) {
            abort(403, 'No tienes permiso para ver los recordatorios de este usuario.');
        }
        $mascotaIds = $usuario->mascotas->pluck('id');

        $query = Recordatorio::whereIn('mascota_id', $mascotaIds)
            ->with('mascota');

        if ($request->filled('mascota')) {
            $query->whereHas('mascota', function ($q) use ($request) {
                $q->where('nombre', $request->mascota);
            });
        }

        if ($request->filled('titulo')) {
            $query->where('titulo', 'like', '%' . $request->titulo . '%');
        }
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', '>=', $request->fecha);
        }


        if ($request->filled('estado')) {
            $query->where('realizado', $request->estado);
        }

        $recordatorios = $query->orderBy('realizado')->orderBy('fecha')->paginate(10)->appends($request->all());

        // Mascotas únicas para el dropdown del filtro
        $mascotasUnicas = $usuario->mascotas->pluck('nombre')->unique()->filter()->values();

        $hoy = Carbon::today();
        $manana = now()->addDay()->toDateString();
        $pasado = now()->addDays(2)->toDateString();

        $usuariosDisponibles = $usuario->is_admin
            ? \App\Models\User::orderBy('name')->get(['id', 'name'])
            : collect();

        // Generar el HTML del select de usuarios para el filtro (solo admin)
        $selectUsuariosHtml = '';
        if ($usuario->is_admin) {
            $selectUsuariosHtml = '<select name="usuario_id" class="w-48 text-xs h-8 px-2 py-1 border border-gray-300 rounded dark:bg-slate-600 dark:text-white dark:border-gray-500">';
            $selectUsuariosHtml .= '<option value="">Todos los usuarios</option>';
            foreach ($usuariosDisponibles as $userOption) {
                $selected = request('usuario_id') == $userOption->id ? 'selected' : '';
                $selectUsuariosHtml .= '<option value="' . $userOption->id . '" ' . $selected . '>' . e($userOption->name) . '</option>';
            }
            $selectUsuariosHtml .= '</select>';
        }

        return view('recordatorios.personales', compact('recordatorios', 'usuario', 'mascotasUnicas', 'hoy', 'manana', 'pasado', 'usuariosDisponibles', 'selectUsuariosHtml'));
    }

    /**
     * Mostrar calendario de recordatorios
     */
    public function calendario(User $usuario, Request $request)
    {
        $mascotaIds = $usuario->mascotas->pluck('id');

        $query = Recordatorio::whereIn('mascota_id', $mascotaIds)
            ->with('mascota');

        // Filtros opcionales para el calendario
        if ($request->filled('mascota')) {
            $query->whereHas('mascota', function ($q) use ($request) {
                $q->where('nombre', $request->mascota);
            });
        }

        if ($request->filled('estado')) {
            $query->where('realizado', $request->estado);
        }

        // Obtener recordatorios de todo el año actual para el calendario (excluyendo citas)
        $recordatorios = $query->where('es_cita', false)
            ->whereYear('fecha', now()->year)
            ->orderBy('fecha')
            ->get();

        // Obtener historial médico de las mascotas del usuario
        $historialMedico = \App\Models\HistorialMedico::whereIn('mascota_id', $mascotaIds)
            ->with('mascota')
            ->whereYear('fecha', now()->year)
            ->orderBy('fecha')
            ->get();

        // Obtener citas (recordatorios con es_cita = true)
        $citas = Recordatorio::whereIn('mascota_id', $mascotaIds)
            ->where('es_cita', true)
            ->with('mascota')
            ->whereYear('fecha', now()->year)
            ->orderBy('fecha')
            ->get();

        return view('recordatorios.calendario', compact('recordatorios', 'historialMedico', 'citas', 'usuario'));
    }

    /**
     * Cambia el estado de realizado/pendiente del recordatorio si la fecha es hoy o futura
     */
    public function cambiarEstado(Request $request, $hashid)
    {
        \Log::info('cambiarEstado llamado', [
            'hashid' => $hashid,
            'request_data' => $request->all()
        ]);
        
        $recordatorio = $this->resolveRecordatorio($hashid);
        
        \Log::info('Recordatorio encontrado', [
            'recordatorio_id' => $recordatorio->id,
            'titulo' => $recordatorio->titulo,
            'estado_actual' => $recordatorio->realizado,
            'fecha' => $recordatorio->fecha
        ]);
        
        // Verificar permisos
        $authUser = auth()->user();
        if ($authUser->id !== $recordatorio->mascota->user_id && !$authUser->is_admin) {
            abort(403, 'No tienes permiso para modificar este recordatorio.');
        }
        
        if ($recordatorio->fecha->isPast() && !$recordatorio->fecha->isToday()) {
            return back()->with('error', 'No se puede modificar el estado de un recordatorio cuya fecha es anterior a hoy.');
        }

        $nuevoEstado = (bool) $request->input('nuevo_estado');
        
        \Log::info('Actualizando estado', [
            'estado_anterior' => $recordatorio->realizado,
            'nuevo_estado' => $nuevoEstado
        ]);
        
        $recordatorio->realizado = $nuevoEstado;
        $recordatorio->save();

        \Log::info('Estado actualizado', [
            'estado_final' => $recordatorio->realizado,
            'guardado' => $recordatorio->wasRecentlyCreated ? 'nuevo' : 'actualizado'
        ]);

        // Limpiar la caché de recordatorios del perfil si corresponde
        $usuarioId = $recordatorio->mascota->user_id;
        $cacheKey = "recordatorios_profile_user_{$usuarioId}";
        \Cache::forget($cacheKey);

        return back()->with('success', 'Estado del recordatorio actualizado correctamente.');
    }

    /**
     * Listar los recordatorios de un usuario de forma RESTful
     */
    public function indexPorUsuario(User $usuario, Request $request)
    {
        $authUser = auth()->user();
        if ($authUser->id !== $usuario->id && !$authUser->is_admin) {
            abort(403, 'No tienes permiso para ver los recordatorios de este usuario.');
        }
        $mascotaIds = $usuario->mascotas->pluck('id');

        $query = Recordatorio::whereIn('mascota_id', $mascotaIds)
            ->with('mascota');

        if ($request->filled('mascota')) {
            $query->whereHas('mascota', function ($q) use ($request) {
                $q->where('nombre', $request->mascota);
            });
        }

        if ($request->filled('titulo')) {
            $query->where('titulo', 'like', '%' . $request->titulo . '%');
        }
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', '>=', $request->fecha);
        }

        if ($request->filled('estado')) {
            $query->where('realizado', $request->estado);
        }

        $recordatorios = $query->orderBy('realizado')->orderBy('fecha')->paginate(10)->appends($request->except('page'));

        // Mascotas únicas para el dropdown del filtro
        $mascotasUnicas = $usuario->mascotas->pluck('nombre')->unique()->filter()->values();

        return view('recordatorios.personales', compact('recordatorios', 'usuario', 'mascotasUnicas'));
    }

}
