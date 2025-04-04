<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use App\Models\User;
use App\Enums\Especie;
use App\Enums\Sexo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
class MascotaController extends Controller
{
    // obliga a que solo usuarios autenticados puedan acceder a los métodos del controlador.
        public function __construct()
        {
            $this->middleware('auth');
        }
    public function index(Request $request)
    {
        $query = Mascota::with('usuario');
        if(!auth()->user()->is_admin){
        $query->where('user_id', auth()->id());
        }
        $orden = $request->get('ordenar', 'id'); // columna por defecto
        $direccion = $request->get('direccion', 'asc'); // dirección por defecto

        // Validar columnas permitidas para evitar SQL injection
            $columnasPermitidas = ['id', 'nombre', 'especie', 'sexo'];
            if (!in_array($orden, $columnasPermitidas)) {
                $orden = 'id';
            }
            if ($request->has('busqueda') && $request->busqueda !== '') {
                $query->where('nombre', 'like', '%' . $request->busqueda . '%');
            }

            $mascotas = $query->orderBy($orden,$direccion)->paginate(10)
            ->appends($request->all()); // mantiene los parámetros en los links de paginación;

            return view('mascotas.index', compact('mascotas'));
    }

    public function create()
    {
        $especies = Especie::cases();
        $sexos = Sexo::cases();
        return view('mascotas.create', compact('especies','sexos'));
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

        $especies = Especie::cases();
        $sexos = Sexo::cases();
          $nombreUsuario = $mascota->usuario->name;
        return view('mascotas.edit', compact('mascota', 'especies','nombreUsuario','sexos'));
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
    $mascota->load('usuario', 'historial'); // cargar relaciones

    return view('mascotas.show', compact('mascota'));
}


}
