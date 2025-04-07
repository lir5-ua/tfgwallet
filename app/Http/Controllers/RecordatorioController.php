<?php

namespace App\Http\Controllers;
use App\Models\Mascota;
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
   $query = Recordatorio::with('mascota')
           ->whereDate('fecha', '>=', Carbon::today())->where('realizado',false);

        // si el usuario quiere, ordenamos por fecha
       if ($request->query('sort') === 'fecha') {
           $query->orderBy('fecha');
           }

       $recordatorios = $query->get()->groupBy('mascota.nombre');

       return view('recordatorios.index', compact('recordatorios'));
   }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Mascota $mascota)
    {
        return view('recordatorios.create', compact('mascota'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Mascota $mascota)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'fecha' => 'required|date|after_or_equal:today',
            'descripcion' => 'nullable|string',
        ],
        [
            'fecha.after_or_equal' => 'La fecha debe ser hoy o posterior.',
        ]);

        // Crear el recordatorio y asociarlo a la mascota
        $mascota->recordatorios()->create([
            'titulo' => $validated['titulo'],
            'fecha' => $validated['fecha'],
            'descripcion' => $validated['descripcion'] ?? null,
            'realizado' => false,
        ]);

        // Redirigir con mensaje
        return redirect()
            ->route('mascotas.show', $mascota->id)
            ->with('success', 'Recordatorio creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Recordatorio $recordatorio)
    {
        return view('recordatorios.show', compact('recordatorio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Recordatorio::destroy($id);

        $recordatorios = Recordatorio::with('mascota')
            ->whereDate('fecha', '>=', Carbon::today())
            ->orderBy('fecha')
            ->get()
            ->groupBy('mascota.nombre');

        return view('recordatorios.index', compact('recordatorios'));
    }

public function marcarComoVisto(Recordatorio $recordatorio)
{
    $recordatorio->realizado = true;
    $recordatorio->save();

    return back(); // vuelve a la página actual
}


}
