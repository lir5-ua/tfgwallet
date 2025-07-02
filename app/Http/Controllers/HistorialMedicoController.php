<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use App\Models\HistorialMedico;
use Illuminate\Http\Request;
use App\Enums\TipoHistorial;
use Vinkla\Hashids\Facades\Hashids;

class HistorialMedicoController extends Controller
{


    protected $casts = [
        'tipo' => TipoHistorial::class,
    ];

    
    protected function resolveMascota($hashid)
    {
       // dd("Incoming Mascota HashID (Historial):", $hashid, "Decoded IDs (Historial):", \Vinkla\Hashids\Facades\Hashids::decode($hashid));
        $ids = \Vinkla\Hashids\Facades\Hashids::decode($hashid);
        if (empty($ids)) { abort(404); }
        return \App\Models\Mascota::findOrFail($ids[0]);
    }
    protected function resolveHistorialMedico($hashid)
    {
        $ids = \Vinkla\Hashids\Facades\Hashids::decode($hashid);
        if (empty($ids)) { abort(404); }
        return \App\Models\HistorialMedico::findOrFail($ids[0]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $hashid = null)
    {
        $mascota = $hashid ? $this->resolveMascota($hashid) : null;
        if ($mascota && $mascota->exists) {
            $user = auth()->user();
            if ($user->id !== $mascota->user_id && !$user->is_admin) {
                abort(403, 'No tienes permiso para ver el historial de esta mascota.');
            }
            $query = $mascota->historial();
        } else {
            $query = HistorialMedico::query()->with('mascota');
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('veterinario')) {
            $query->where('veterinario', 'like', '%' . $request->veterinario . '%');
        }

        $historiales = $query->paginate(10)->appends($request->except('page'));
        $tipos = \App\Enums\TipoHistorial::cases();
        $filtros = $request->only(['fecha', 'tipo', 'veterinario']);
        $usuario = $mascota && $mascota->exists ? $mascota->user : null;

        return view('historial.index', compact('mascota', 'historiales', 'usuario', 'tipos', 'filtros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($hashid)
    {
        $mascota = $this->resolveMascota($hashid);
        $user = auth()->user();
        if ($user->id !== $mascota->user_id && !$user->is_admin) {
            abort(403, 'No tienes permiso para crear historial para esta mascota.');
        }
        return view('historial.create', compact('mascota'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $hashid)
    {
        $mascota = $this->resolveMascota($hashid);
        $user = auth()->user();
        if ($user->id !== $mascota->user_id && !$user->is_admin) {
            abort(403, 'No tienes permiso para añadir historial a esta mascota.');
        }
        $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|string',
            'descripcion' => 'required|string',
            'veterinario' => 'nullable|string'
        ]);

        $mascota->historial()->create($request->all());

        return redirect()->route('mascotas.historial.index', $mascota->hashid)->with('success', 'Entrada creada');
    }

    /**
     * Display the specified resource.
     */
    public function show($mascotaHashid, $historialHashid)
    {
        $mascota = $this->resolveMascota($mascotaHashid);
        $historial = $this->resolveHistorialMedico($historialHashid);

        if ($historial->mascota_id !== $mascota->id) {
            abort(404, 'La entrada de historial médico no pertenece a esta mascota.');
        }

        $user = auth()->user();
        if ($user->id !== $mascota->user_id && !$user->is_admin) {
            abort(403, 'No tienes permiso para ver el historial de esta mascota.');
        }

        return view('historial.show', compact('mascota', 'historial'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($mascotaHashid, $historialHashid)
    {
        $mascota = $this->resolveMascota($mascotaHashid);
        $historial = $this->resolveHistorialMedico($historialHashid);
        $user = auth()->user();
        if ($user->id !== $mascota->user_id && !$user->is_admin) {
            abort(403, 'No tienes permiso para editar el historial de esta mascota.');
        }
        session(['return_to_after_update' => url()->previous()]);
        return view('historial.edit', compact('mascota', 'historial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $mascotaHashid, $historialHashid)
    {
        $mascota = $this->resolveMascota($mascotaHashid);
        $historial = $this->resolveHistorialMedico($historialHashid);
        $user = auth()->user();
        if ($user->id !== $mascota->user_id && !$user->is_admin) {
            abort(403, 'No tienes permiso para actualizar el historial de esta mascota.');
        }
        $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|string',
            'descripcion' => 'required|string',
            'veterinario' => 'nullable|string'
        ]);

        $historial->update($request->all());

        return redirect()->route('mascotas.show', $mascotaHashid)->with('success', 'Entrada actualizada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($mascotaHashid, $historialHashid)
    {
        $mascota = $this->resolveMascota($mascotaHashid);
        $historial = $this->resolveHistorialMedico($historialHashid);
        $user = auth()->user();
        if ($user->id !== $mascota->user_id && !$user->is_admin) {
            abort(403, 'No tienes permiso para eliminar el historial de esta mascota.');
        }
        $historial->delete();
        return redirect()->back()->with('success', 'Entrada historial eliminada.');
    }
}
