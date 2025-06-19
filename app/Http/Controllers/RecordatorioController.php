<?php

namespace App\Http\Controllers;
use App\Models\Mascota;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Recordatorio;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache; 
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
        $usuario = $request->has('usuario_id') && auth()->user()->is_admin
            ? User::findOrFail($request->usuario_id)
            : auth()->user();

        $query = Recordatorio::with('mascota')
            ->whereHas('mascota', function ($q) use ($usuario) {
                $q->where('user_id', $usuario->id);
            })
            ->whereDate('fecha', '>=', Carbon::today())
            ->where('realizado', false);

        if ($request->query('sort') === 'fecha') {
            $query->orderBy('fecha');
        }

        $recordatorios = $query->paginate(10)->appends($request->except('page'));

        return view('recordatorios.index', compact('recordatorios', 'usuario'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Mascota $mascota = null)
    {
        // Si se pasa la mascota como parámetro de ruta (rutas anidadas)
        if ($mascota) {
            $mascotas = collect([$mascota]);
        }
        // Si se pasa mascota_id por query string (rutas normales)
        elseif ($request->has('mascota_id')) {
            $mascota = Mascota::findOrFail($request->mascota_id);
            $mascotas = collect([$mascota]);
        }
        // Si no se especifica mascota, mostrar todas las mascotas del usuario
        else {
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mascota_id' => 'required|exists:mascotas,id',
            'titulo' => 'required|string|max:255',
            'fecha' => 'required|date',
            'descripcion' => 'nullable|string',
        ]);

        // ⚠️ Aquí obtenemos la mascota por ID
        $mascota = Mascota::find($validated['mascota_id']);

        // ✅ Ahora creamos el recordatorio asociado
        $mascota->recordatorios()->create([
            'titulo' => $validated['titulo'],
            'fecha' => $validated['fecha'],
            'descripcion' => $validated['descripcion'],
            'realizado' => false,
        ]);
        //dd(session('return_to_after_update'));

        return redirect(session('return_to_after_update', route('mascotas.show', $mascota)))
            ->with('success', 'Historial creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Recordatorio $recordatorio)
    {
        $referer = url()->previous();

        // Solo guardar si NO venimos de edit (para evitar bucles)
        if (!str_contains($referer, '/edit')) {
            session(['return_to_after_update' => $referer]);
        }
        return view('recordatorios.show', compact('recordatorio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recordatorio $recordatorio)
    {

        return view('recordatorios.edit', compact('recordatorio'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recordatorio $recordatorio)
    {
        // 1. Validate the incoming request data
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'realizado' => 'nullable|boolean', // This handles the checkbox
        ]);

        // 2. Prepare the data for the update
        $updateData = [
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
        ];

        // 3. Conditionally add 'realizado' (and 'visto' if applicable) to the update data
        // This logic ensures that 'realizado' is only updated if the recordatorio's date
        // allows the checkbox to be displayed and manipulated.
        if ($recordatorio->fecha->isToday() || $recordatorio->fecha->isFuture()) {
            // $request->has('realizado') will be true if the checkbox was checked (value='1' submitted)
            // and false if it was unchecked (no value submitted for 'realizado').
            $updateData['realizado'] = $request->has('realizado');

            // If your 'visto' column should always mirror 'realizado', add it here:
            // $updateData['visto'] = $request->has('realizado');
        }
        // If the recordatorio date is in the past and not today, the checkbox isn't shown,
        // so we don't include 'realizado' in $updateData, preserving its current value.

        // 4. Perform the update
        $recordatorio->update($updateData);

        // 5. Clear the cache for the associated user's reminders
        $usuarioId = $recordatorio->mascota->user_id;
        $cacheKey = "recordatorios_profile_user_{$usuarioId}";
        Cache::forget($cacheKey);

        // 6. Redirect with a success message
        return redirect(session()->pull('return_to_after_update', route('usuarios.show', auth()->user())))
            ->with('success', 'Recordatorio actualizado correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $recordatorio = Recordatorio::with('mascota.usuario')->findOrFail($id);

        // Obtener el usuario antes de eliminar
        $usuario = $recordatorio->mascota->usuario;
        $recordatorio->delete();
        $recordatorios = Recordatorio::with('mascota')
            ->whereDate('fecha', '>=', Carbon::today())
            ->orderBy('fecha')
            ->get()
            ->groupBy('mascota.nombre');

        return redirect()->back()->with('success', 'Entrada historial eliminada');
    }

    public function marcarComoVisto(Recordatorio $recordatorio)
    {
        $recordatorio->realizado = true;
        $recordatorio->save();

        $usuarioId = $recordatorio->mascota->user_id;

        // Construct the exact cache key used in UserController@show
        $cacheKey = "recordatorios_profile_user_{$usuarioId}";

        // Forget (remove) this specific cache entry
        Cache::forget($cacheKey);

        // Redirect back to the previous page.
        // Now, when the profile page reloads, it will re-fetch data from the DB
        // because its cache entry has been cleared.
        return back()->with('success', 'Estado actualizado');
    }

    public function mascota()
    {
        return $this->belongsTo(Mascota::class);
    }

    public function personales(User $usuario, Request $request)
    {
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

}
