<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Veterinario;
use Illuminate\Support\Facades\Hash;

class VeterinarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:veterinarios')->only('show');
    }

    public function index()
    {
        $veterinarios = Veterinario::all();
        return response()->json($veterinarios);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:veterinarios,email',
            'numero_colegiado' => 'required|string|unique:veterinarios,numero_colegiado',
            'password' => 'required|string|min:6',
            'telefono' => 'nullable|string',
            'direccion' => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $veterinario = Veterinario::create($validated);
        return response()->json($veterinario, 201);
    }

    public function show(Veterinario $veterinario)
    {
        return view('veterinarios.show', compact('veterinario'));
    }

    public function calendario(Veterinario $veterinario, Request $request)
    {
        // Verificar que el veterinario autenticado sea el mismo que se está consultando
        $authVeterinario = auth()->guard('veterinarios')->user();
        if ($authVeterinario->id !== $veterinario->id) {
            abort(403, 'No tienes permiso para ver el calendario de otro veterinario.');
        }

        // Obtener todas las citas (recordatorios con es_cita = true) que el veterinario ha programado
        $query = \App\Models\Recordatorio::where('es_cita', true)
            ->whereHas('mascota.usuario', function ($q) use ($veterinario) {
                $q->whereHas('veterinarios', function ($vq) use ($veterinario) {
                    $vq->where('veterinario_id', $veterinario->id);
                });
            })
            ->with(['mascota.usuario']);

        // Filtros opcionales para el calendario
        if ($request->filled('cliente')) {
            $query->whereHas('mascota.usuario', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->cliente . '%');
            });
        }

        if ($request->filled('mascota')) {
            $query->whereHas('mascota', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->mascota . '%');
            });
        }

        if ($request->filled('estado')) {
            $query->where('realizado', $request->estado);
        }

        // Obtener citas de todo el año actual para el calendario
        $citas = $query->whereYear('fecha', now()->year)
            ->orderBy('fecha')
            ->get();

        return view('veterinarios.calendario', compact('citas', 'veterinario'));
    }

    /**
     * Mostrar el índice de usuarios vinculados al veterinario
     */
    public function usuarios(Veterinario $veterinario)
    {
        // Verificar que el veterinario autenticado sea el mismo que se está consultando
        $authVeterinario = auth()->guard('veterinarios')->user();
        if ($authVeterinario->id !== $veterinario->id) {
            abort(403, 'No tienes permiso para ver los usuarios de otro veterinario.');
        }

        // Obtener usuarios vinculados al veterinario con sus mascotas
        $usuarios = $veterinario->usuarios()
            ->with(['mascotas' => function ($query) use ($veterinario) {
                // Solo incluir mascotas que han tenido citas con este veterinario
                $query->whereHas('recordatorios', function ($rq) use ($veterinario) {
                    $rq->where('es_cita', true);
                });
            }])
            ->whereHas('mascotas.recordatorios', function ($query) use ($veterinario) {
                $query->where('es_cita', true);
            })
            ->get();

        return view('veterinarios.usuarios', compact('usuarios', 'veterinario'));
    }

    /**
     * Mostrar las mascotas de un usuario específico vinculadas al veterinario
     */
    public function mascotasUsuario(Veterinario $veterinario, $usuarioHashid)
    {
        // Verificar que el veterinario autenticado sea el mismo que se está consultando
        $authVeterinario = auth()->guard('veterinarios')->user();
        if ($authVeterinario->id !== $veterinario->id) {
            abort(403, 'No tienes permiso para ver las mascotas de otro veterinario.');
        }

        // Decodificar el hashid del usuario
        $ids = \Vinkla\Hashids\Facades\Hashids::decode($usuarioHashid);
        if (count($ids) === 0) {
            abort(404);
        }
        
        $usuario = \App\Models\User::findOrFail($ids[0]);

        // Verificar que el usuario esté vinculado al veterinario
        if (!$veterinario->usuarios()->where('user_id', $usuario->id)->exists()) {
            abort(403, 'No tienes permiso para ver las mascotas de este usuario.');
        }

        // Obtener mascotas del usuario que han tenido citas con este veterinario
        $mascotas = $usuario->mascotas()
            ->whereHas('recordatorios', function ($query) use ($veterinario) {
                $query->where('es_cita', true);
            })
            ->with(['recordatorios' => function ($query) use ($veterinario) {
                $query->where('es_cita', true)
                    ->orderBy('fecha', 'desc');
            }])
            ->get();

        return view('veterinarios.mascotas-usuario', compact('mascotas', 'usuario', 'veterinario'));
    }
}
