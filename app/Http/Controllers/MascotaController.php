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
$hoy = Carbon::today();

    // Recordatorios para hoy del usuario autenticado
    $recordatoriosHoy = Recordatorio::whereDate('fecha', $hoy)
        ->whereHas('mascota', function ($q) use ($usuario) {
            if (auth()->user()->is_admin && $usuario) {
                $q->where('user_id', $usuario->id);
            } else {
                $q->where('user_id', auth()->id());
            }
        })->where('realizado',false)
        ->get();

        $query = Mascota::with('usuario');
        $user = auth()->user();

        // Usuario normal → ve solo sus mascotas
        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        }
        // Admin viendo mascotas de un usuario concreto
        elseif ($usuario !== null) {
            $query->where('user_id', $usuario->id);
        }
        // Admin sin parámetro → ve todas

        // Ordenar y buscar
        $orden = $request->get('ordenar', 'id');
        $direccion = $request->get('direccion', 'asc');

        $columnasPermitidas = ['id', 'nombre', 'especie', 'sexo'];
        if (!in_array($orden, $columnasPermitidas)) {
            $orden = 'id';
        }

        if ($request->has('busqueda') && $request->busqueda !== '') {
            $query->where('nombre', 'like', '%' . $request->busqueda . '%');
        }

        $mascotas = $query->orderBy($orden, $direccion)
                          ->paginate(10)
                          ->appends($request->all());
$titulo = '';

// Usuario normal
if (!$user->is_admin) {
    $titulo = 'Mis mascotas';
}
// Admin viendo mascotas de un usuario
elseif ($usuario !== null) {
    $titulo = 'Mascotas de ' . $usuario->name;
}
// Admin sin filtro → todas las mascotas
else {
    $titulo = 'Todas las mascotas';
}
        return view('mascotas.index', compact('mascotas','titulo','recordatoriosHoy'));
    }

    public function create()
    {
        $sexos = Sexo::cases();
        $razasPorEspecie = Especie::todasLasRazasPorEspecie();
        $especies = Especie::labels();

        return view('mascotas.create', compact('sexos', 'razasPorEspecie', 'especies'));
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
           }else{
           $rutaImagen = null;
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
        return view('mascotas.edit', compact('mascota', 'especies','nombreUsuario','razasPorEspecie','sexos'));
    }

    public function update(Request $request, Mascota $mascota)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'required|string',
            'nombre_usuario' => 'required|string|exists:users,name',
            'raza' => 'nullable|string|max:255',
            'sexo' => 'nullable|string',
              'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'fecha_nacimiento' => 'nullable|date',
            'notas' => 'nullable|string',
        ]);
        $usuario = User::where('name', $request->nombre_usuario)->first();
         if ($request->hasFile('imagen')) {
           $rutaImagen = $request->file('imagen')->store('mascotas', 'public');
       }else{
       $rutaImagen = null;
       }
        $mascota->update([
            'nombre' => $request->nombre,
            'especie' => \App\Enums\Especie::from($request->especie),
            'user_id' => $usuario->id,
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

            $recordatorios = $mascota->recordatorios()
                ->where('realizado', false)
                ->whereDate('fecha', '>=', Carbon::today())
                ->orderBy('fecha')
                ->get();
    $mascota->load('usuario', 'historial'); // cargar relaciones
    return view('mascotas.show', compact('mascota','recordatorios'));
}


}
