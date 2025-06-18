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

        // 🔔 RECORDATORIOS - Optimizado con eager loading
        $hoy = now()->toDateString();
        $manana = now()->addDay()->toDateString();
        $pasado = now()->addDays(2)->toDateString();

        $recordatorios = Recordatorio::whereIn('fecha', [$hoy, $manana, $pasado])
            ->whereHas('mascota', function ($q) use ($usuario, $user) {
                if ($user->is_admin && $usuario) {
                    $q->where('user_id', $usuario->id);
                } else {
                    $q->where('user_id', $user->id);
                }
            })
            ->where('realizado', false)
            ->with(['mascota.usuario']) // Eager loading optimizado
            ->get();

        // 🐶 CONSULTA BASE - Optimizada con eager loading
        $query = Mascota::with(['usuario', 'historial' => function($q) {
            $q->latest()->limit(5); // Solo los últimos 5 historiales
        }, 'recordatorios' => function($q) {
            $q->where('realizado', false)->where('fecha', '>=', now()->toDateString());
        }]);

        // 👤 FILTRAR POR USUARIO
        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        } elseif ($usuario !== null) {
            $query->where('user_id', $usuario->id);
        }

        // 🔍 FILTROS DE BUSQUEDA - Optimizados con índices
        if ($request->filled('busqueda')) {
            $query->where('nombre', 'like', '%' . $request->busqueda . '%');
        }

        if ($request->filled('especie')) {
            $query->where('especie', $request->especie);
        }

        if ($request->filled('raza')) {
            $query->where('raza', $request->raza);
        }

        if ($request->filled('sexo')) {
            $query->where('sexo', $request->sexo);
        }

        // 📊 ORDENACIÓN SEGURA
        $orden = $request->get('ordenar', 'id');
        $direccion = $request->get('direccion', 'asc');
        $columnasPermitidas = ['id', 'nombre', 'especie', 'sexo'];
        if (!in_array($orden, $columnasPermitidas)) {
            $orden = 'id';
        }

        // 📄 PAGINACIÓN - Optimizada
        $mascotas = $query->orderBy($orden, $direccion)
            ->paginate(10)
            ->appends($request->all());

        // 🏷️ TÍTULO PERSONALIZADO
        $titulo = match (true) {
            !$user->is_admin => 'Mis mascotas',
            $usuario !== null => 'Mascotas de ' . $usuario->name,
            default => 'Todas las mascotas',
        };
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
           // Asignar imagen por defecto según la especie
           $especie = \App\Enums\Especie::from($request->especie);
           $rutaImagen = 'mascotas/default_' . strtolower($especie->name) . '.jpg';
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
           // Mantener la imagen actual o asignar imagen por defecto según la especie
           $especie = \App\Enums\Especie::from($request->especie);
           $rutaImagen = $mascota->imagen ?: 'mascotas/default_' . strtolower($especie->name) . '.jpg';
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
public function show(Mascota $mascota)
{
    // Lógica para mostrar recordatorios inteligentemente
    $hoy = Carbon::today();
    $manana = Carbon::tomorrow();
    
    // Primero verificar si hay recordatorios para mañana
    $recordatoriosManana = $mascota->recordatorios()
        ->where('realizado', false)
        ->whereDate('fecha', $manana)
        ->orderBy('fecha')
        ->get();
    
    // Si hay recordatorios para mañana, mostrar solo esos
    if ($recordatoriosManana->count() > 0) {
        $recordatorios = $recordatoriosManana;
    } else {
        // Si no hay para mañana, verificar si hay para hoy
        $recordatoriosHoy = $mascota->recordatorios()
            ->where('realizado', false)
            ->whereDate('fecha', $hoy)
            ->orderBy('fecha')
            ->get();
        
        // Si hay para hoy, mostrar solo esos
        if ($recordatoriosHoy->count() > 0) {
            $recordatorios = $recordatoriosHoy;
        } else {
            // Si no hay ni para hoy ni para mañana, mostrar todos los futuros
            $recordatorios = $mascota->recordatorios()
                ->where('realizado', false)
                ->whereDate('fecha', '>=', $hoy)
                ->orderBy('fecha')
                ->get();
        }
    }
    
    $mascota->load('usuario', 'historial'); // cargar relaciones
    session(['return_to_after_update' => url()->current()]);

    return view('mascotas.show', compact('mascota','recordatorios'));
}


}
