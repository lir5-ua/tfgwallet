<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use App\Models\HistorialMedico;
use Illuminate\Http\Request;
use App\Enums\TipoHistorial;

class HistorialMedicoController extends Controller
{

    protected $casts = [
        'tipo' => TipoHistorial::class,
    ];


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Mascota $mascota = null)
    {
        if ($mascota && $mascota->exists) {
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
    public function create(Mascota $mascota)
        {
            return view('historial.create', compact('mascota'));
        }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request, Mascota $mascota)
        {
            $request->validate([
                'fecha' => 'required|date',
                'tipo' => 'required|string',
                'descripcion' => 'required|string',
                'veterinario' => 'nullable|string'
            ]);

            $mascota->historial()->create($request->all());

            return redirect()->route('mascotas.historial.index', $mascota)->with('success', 'Entrada creada');
        }

    /**
     * Display the specified resource.
     */
    public function show(Mascota $mascota, HistorialMedico $historial)
        {
            // Evitar sobrescribir si vienes de editar o crear
            if (!str_contains(url()->previous(), 'historial') && !str_contains(url()->previous(), 'edit')) {
                session(['return_to_after_update' => url()->previous()]);
            }

            return view('historial.show', compact('mascota', 'historial'));
        }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mascota $mascota, HistorialMedico $historial)
        {
            session(['return_to_after_update' => url()->previous()]);
            return view('historial.edit', compact('mascota', 'historial'));
        }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mascota $mascota, HistorialMedico $historial)
    {
        $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|string',
            'descripcion' => 'required|string',
            'veterinario' => 'nullable|string'
        ]);

        $historial->update($request->all());

        return redirect()->route('mascotas.show', $mascota)->with('success', 'Entrada actualizada');
    }


    /**
     * Remove the specified resource from storage.
     */
     public function destroy(Mascota $mascota, HistorialMedico $historial)
        {
            $historial->delete();
            return redirect()->back()->with('success', 'Entrada historial eliminada');

        }
}
