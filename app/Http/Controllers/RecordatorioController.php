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

        $mascotasUnicas = $usuario->mascotas->pluck('nombre')->unique()->filter()->values();

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

        return view('recordatorios.personales', compact('recordatorios', 'usuario', 'mascotasUnicas', 'hoy', 'manana', 'pasado'));
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

        // Obtener recordatorios de todo el año actual para el calendario
        $recordatorios = $query->whereYear('fecha', now()->year)
            ->orderBy('fecha')
            ->get();

        // Obtener historial médico de las mascotas del usuario
        $historialMedico = \App\Models\HistorialMedico::whereIn('mascota_id', $mascotaIds)
            ->with('mascota')
            ->whereYear('fecha', now()->year)
            ->orderBy('fecha')
            ->get();

        return view('recordatorios.calendario', compact('recordatorios', 'historialMedico', 'usuario'));
    }

    /**
     * Cambia el estado de realizado/pendiente del recordatorio si la fecha es hoy o futura
     */
    public function cambiarEstado(Request $request, Recordatorio $recordatorio)
    {
        if ($recordatorio->fecha->isPast() && !$recordatorio->fecha->isToday()) {
            return back()->with('error', 'No se puede modificar el estado de un recordatorio cuya fecha es anterior a hoy.');
        }

        $nuevoEstado = (bool) $request->input('nuevo_estado');
        $recordatorio->visto = $nuevoEstado;
        $recordatorio->realizado = $nuevoEstado;
        $recordatorio->save();

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
