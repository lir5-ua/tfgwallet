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
    public function index(Mascota $mascota)
        {

            $historiales = $mascota->historial()->get();
            $usuario = $mascota->user; // relación Mascota belongsTo User

            return view('historial.index', compact('mascota', 'historiales', 'usuario'));
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

            return redirect()->route('mascotas.historial.index', $mascota)->with('success', 'Entrada actualizada');
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
