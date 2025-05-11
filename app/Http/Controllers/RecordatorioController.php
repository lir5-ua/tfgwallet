<?php

namespace App\Http\Controllers;
use App\Models\Mascota;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Recordatorio;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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

        $recordatorios = $query->get()->groupBy('mascota.nombre');


        return view('recordatorios.index', compact('recordatorios', 'usuario'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $mascota = null;
        if ($request->has('mascota_id')) {
            $mascota = Mascota::findOrFail($request->mascota_id);
            $mascotas = collect([$mascota]);
        } else {
            $usuarioId = $request->get('usuario_id');
            $usuario = $usuarioId ? User::findOrFail($usuarioId) : auth()->user();
            $mascotas = $usuario->mascotas;
            //dd($usuario);
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
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
        ]);

        $recordatorio->update([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
        ]);

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

        return back()->with('success', 'Estado actualizado'); // vuelve a la página actual
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

        $recordatorios = $query->orderBy('realizado')->orderBy('fecha')->get();

        // Mascotas únicas para el dropdown del filtro
        $mascotasUnicas = $usuario->mascotas->pluck('nombre')->unique()->filter()->values();

        return view('recordatorios.personales', compact('recordatorios', 'usuario', 'mascotasUnicas'));
    }



}
