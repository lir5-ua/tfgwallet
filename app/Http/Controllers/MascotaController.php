<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use App\Models\User;
use App\Models\Recordatorio;

use App\Enums\Especie;
use App\Enums\Sexo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class MascotaController extends Controller
{
    // obliga a que solo usuarios autenticados puedan acceder a los métodos del controlador.
        public function __construct()
        {
            $this->middleware('auth');
        }
    public function index(Request $request, User $usuario = null)
    {
        $sexos = Sexo::cases();
        $razasPorEspecie = Especie::todasLasRazasPorEspecie();
        $especies = Especie::labels();
        $hoy = Carbon::today();
        $user = auth()->user();

        // 🔔 RECORDATORIOS - Optimizado con cache
        $cacheKey = "recordatorios_user_{$user->id}_" . md5($request->fullUrl());
        $recordatorios = Cache::remember($cacheKey, 300, function () use ($usuario, $user) {
            $hoy = now()->toDateString();
            $manana = now()->addDay()->toDateString();
            $pasado = now()->addDays(2)->toDateString();

            return Recordatorio::whereIn('fecha', [$hoy, $manana, $pasado])
                ->whereHas('mascota', function ($q) use ($usuario, $user) {
                    if ($user->is_admin && $usuario) {
                        $q->where('user_id', $usuario->id);
                    } else {
                        $q->where('user_id', $user->id);
                    }
                })
                ->where('realizado', false)
                ->with(['mascota.usuario'])
                ->get();
        });

        // 🐶 CONSULTA BASE - Usando cache del modelo con filtros
        $filters = $request->only(['busqueda', 'especie', 'raza', 'sexo']);
        $showAll = $user->is_admin && $request->boolean('show_all');
        if ($showAll && $usuario === null) {
            // Si es admin y quiere ver todas las mascotas
            $mascotas = Mascota::with(['usuario', 'historial' => function($q) {
                $q->latest()->limit(5);
            }, 'recordatorios' => function($q) {
                $q->where('realizado', false)->where('fecha', '>=', now()->toDateString());
            }]);
            // Aplicar filtros directamente en la consulta
            if (!empty($filters['busqueda'])) {
                $mascotas->where('nombre', 'like', '%' . $filters['busqueda'] . '%');
            }
            if (!empty($filters['especie'])) {
                $mascotas->where('especie', $filters['especie']);
            }
            if (!empty($filters['raza'])) {
                $mascotas->where('raza', $filters['raza']);
            }
            if (!empty($filters['sexo'])) {
                $mascotas->where('sexo', $filters['sexo']);
            }
            $mascotas = $mascotas->get();
        } else {
            $userId = $user->is_admin && $usuario ? $usuario->id : $user->id;
            $mascotas = Mascota::getCachedMascotas($userId, $filters);
        }

        // 📊 ORDENACIÓN Y PAGINACIÓN
        $orden = $request->get('ordenar', 'id');
        $direccion = $request->get('direccion', 'asc');
        $columnasPermitidas = ['id', 'nombre', 'especie', 'sexo'];
        if (!in_array($orden, $columnasPermitidas)) {
            $orden = 'id';
        }

        if ($mascotas instanceof \Illuminate\Support\Collection) {
            $mascotas = $mascotas->sortBy([$orden, $direccion]);
            $perPage = 10;
            $page = $request->get('page', 1);
            $mascotas = new \Illuminate\Pagination\LengthAwarePaginator(
                $mascotas->forPage($page, $perPage),
                $mascotas->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $mascotas = $mascotas->sortBy([$orden, $direccion]);
        }

        // 🏷️ TÍTULO PERSONALIZADO
        $titulo = match (true) {
            !$user->is_admin => 'Mis mascotas',
            $usuario !== null => 'Mascotas de ' . $usuario->name,
            default => 'Todas las mascotas',
        };

        $manana = now()->addDay()->toDateString();
        $pasado = now()->addDays(2)->toDateString();

        return view('mascotas.index', compact('especies','razasPorEspecie','sexos','mascotas', 'titulo', 'recordatorios','hoy','manana','pasado'));
    }


    public function create()
    {
        $sexos = Sexo::cases();
        $razasPorEspecie = Especie::todasLasRazasPorEspecie();
        $especies = Especie::labels();

        return view('mascotas.create', [
            'sexos' => $sexos,
            'razasPorEspecie' => $razasPorEspecie,
            'especies' => $especies,
            'mascota' => null, // importante para condicionales en la vista
            'nombreUsuario' => null,
        ]);
    }
    public function recordatorios()
    {
        return $this->hasMany(Recordatorio::class);
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'user_id' => 'required|integer|exists:users,id',
            'especie' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'raza' => 'nullable|string|max:255',
            'sexo' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
            'notas' => 'nullable|string',
            'condiciones' => 'accepted',
        ]);

        $sexoInput = $request->input('sexo');
                $sexo = $sexoInput ? Sexo::from($sexoInput) : null;
         if(!auth()->user()->is_admin){
         $userid = auth()->id();
         }else{
         $userid = $request->user_id;
           }
       if ($request->hasFile('imagen')) {
           $rutaImagen = $request->file('imagen')->store('mascotas', 'public');
       } else {
           // Asignar imagen por defecto
           $rutaImagen = 'mascotas/default_pet.jpg';
       }
        Mascota::create([
            'nombre' => $request->nombre,
            'user_id' => $userid,
            'especie' => \App\Enums\Especie::from($request->especie),
            'raza' => $request->raza,
            'sexo' => $sexo,
            'imagen' => $rutaImagen,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'notas' => $request->notas,
        ]);

        return redirect()->route('mascotas.index')->with('success', 'Mascota creada correctamente.');
        }

    public function edit(Mascota $mascota)
    {
        $sexos = Sexo::cases();
        $razasPorEspecie = Especie::todasLasRazasPorEspecie();
        $especies = Especie::labels();
        $nombreUsuario = $mascota->usuario->name;

        return view('mascotas.create', [
            'mascota' => $mascota,
            'especies' => $especies,
            'nombreUsuario' => $nombreUsuario,
            'razasPorEspecie' => $razasPorEspecie,
            'sexos' => $sexos,
        ]);
    }

    public function update(Request $request, Mascota $mascota)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'required|string',
            'raza' => 'nullable|string|max:255',
            'sexo' => 'nullable|string',
              'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'fecha_nacimiento' => 'nullable|date',
            'notas' => 'nullable|string',
        ]);
        $usuario = User::where('name', $request->nombre_usuario)->first();
         if ($request->hasFile('imagen')) {
           $rutaImagen = $request->file('imagen')->store('mascotas', 'public');
       } else {
           // Mantener la imagen actual o asignar imagen por defecto
           $rutaImagen = $mascota->imagen ?: 'mascotas/default_pet.jpg';
       }
        $mascota->update([
            'nombre' => $request->nombre,
            'especie' => \App\Enums\Especie::from($request->especie),
            'raza' => $request->raza,
            'sexo' => $request->sexo,
            'imagen' => $rutaImagen,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'notas' => $request->notas,
        ]);

        return redirect()->route('mascotas.index')->with('success', 'Mascota actualizada.');
    }

    public function destroy(Mascota $mascota)
    {
        $mascota->delete();
        return redirect()->route('mascotas.index')->with('success', 'Mascota eliminada.');
    }
public function show(Mascota $mascota, Request $request)
{
    $hoy = Carbon::today();
    $manana = Carbon::tomorrow();
    // Recordatorios (sin cambios)
    $recordatoriosManana = $mascota->recordatorios()
        ->where('realizado', false)
        ->whereDate('fecha', $manana)
        ->orderBy('fecha')
        ->get();
    if ($recordatoriosManana->count() > 0) {
        $recordatorios = $recordatoriosManana;
    } else {
        $recordatoriosHoy = $mascota->recordatorios()
            ->where('realizado', false)
            ->whereDate('fecha', $hoy)
            ->orderBy('fecha')
            ->get();
        if ($recordatoriosHoy->count() > 0) {
            $recordatorios = $recordatoriosHoy;
        } else {
            $recordatorios = $mascota->recordatorios()
                ->where('realizado', false)
                ->whereDate('fecha', '>=', $hoy)
                ->orderBy('fecha')
                ->get();
        }
    }
    // Filtros para historial médico
    $historialQuery = $mascota->historial();
    if ($request->filled('fecha')) {
        $historialQuery->whereDate('fecha', $request->fecha);
    }
    if ($request->filled('tipo')) {
        $historialQuery->where('tipo', $request->tipo);
    }
    if ($request->filled('veterinario')) {
        $historialQuery->where('veterinario', 'like', '%' . $request->veterinario . '%');
    }
    $historialFiltrado = $historialQuery->paginate(5)->appends($request->except('page'));
    $mascota->load('usuario');
    session(['return_to_after_update' => url()->current()]);
    return view('mascotas.show', [
        'mascota' => $mascota,
        'recordatorios' => $recordatorios,
        'historialFiltrado' => $historialFiltrado,
    ]);
}


}
