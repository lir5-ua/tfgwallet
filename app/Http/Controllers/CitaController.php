<?php

namespace App\Http\Controllers;


use App\Models\Mascota;
use App\Models\Recordatorio;
use App\Models\User;
use App\Models\Veterinario;
use App\Models\AccesoMascota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;

final class CitaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:veterinarios');
    }

    public function create()
    {
        $veterinario = auth()->guard('veterinarios')->user();
        
        // Obtener usuarios con los que ya ha tratado el veterinario
        $usuariosExistentes = $veterinario->usuarios()->with('mascotas')->get();
        
        return view('citas.create', compact('usuariosExistentes'));
    }

    public function store(Request $request)
    {
        // Debug: ver todos los datos que llegan
        // dd($request->all());
        
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'fecha' => 'required|date|after_or_equal:now',
            'descripcion' => 'nullable|string',
            'tipo_cliente' => 'required|in:nuevo,existente',
            'codigo_mascota' => 'nullable|string|max:16',
            'usuario_id' => 'required|exists:users,id',
            'mascota_id' => 'required|exists:mascotas,id',
        ], [
            'fecha.after_or_equal' => 'La fecha y hora de la cita no puede ser anterior al momento actual.',
        ]);

        $veterinario = auth()->guard('veterinarios')->user();

        // Validación manual para el código de mascota
        if ($validated['tipo_cliente'] === 'nuevo' && empty($validated['codigo_mascota'])) {
            return back()->withErrors(['codigo_mascota' => 'El código de mascota es requerido para clientes nuevos.'])->withInput();
        }

        try {
            DB::beginTransaction();

            if ($validated['tipo_cliente'] === 'nuevo') {
                // Buscar la mascota por código (no usado y no expirado)
                $accesoMascota = AccesoMascota::where('codigo', strtoupper($validated['codigo_mascota']))
                    ->where('expires_at', '>', now()) // Verificar que no haya expirado
                    ->where('usado', false) // Buscar códigos no usados
                    ->first();

                if (!$accesoMascota) {
                    return back()->withErrors(['codigo_mascota' => 'Código de mascota no válido, expirado o ya usado.'])->withInput();
                }

                // Verificar que los IDs coincidan con el código
                if ($accesoMascota->mascota_id != $validated['mascota_id']) {
                    return back()->withErrors(['codigo_mascota' => 'Los datos del código no coinciden.'])->withInput();
                }

                $mascota = Mascota::findOrFail($validated['mascota_id']);
                $usuario = User::findOrFail($validated['usuario_id']);

                // Verificar que la mascota pertenece al usuario
                if ($mascota->user_id != $usuario->id) {
                    return back()->withErrors(['codigo_mascota' => 'La mascota no pertenece al usuario especificado.'])->withInput();
                }

                // Marcar el código como usado por este veterinario
                $accesoMascota->veterinario_id = $veterinario->id;
                $accesoMascota->usado = true;
                $accesoMascota->save();

                // Establecer relación entre veterinario y usuario si no existe
                if (!$veterinario->usuarios()->where('user_id', $usuario->id)->exists()) {
                    $veterinario->usuarios()->attach($usuario->id);
                }
            } else {
                // Para cliente existente, validar que el usuario y mascota existan
                $usuario = User::findOrFail($validated['usuario_id']);
                $mascota = Mascota::where('id', $validated['mascota_id'])
                    ->where('user_id', $usuario->id)
                    ->firstOrFail();
            }

            // Crear el recordatorio/cita
            $cita = Recordatorio::create([
                'mascota_id' => $mascota->id,
                'titulo' => $validated['titulo'],
                'fecha' => $validated['fecha'],
                'descripcion' => $validated['descripcion'],
                'realizado' => false,
                'es_cita' => true,
            ]);

            DB::commit();

            return redirect()->route('veterinarios.perfil', $veterinario->hashid)
                ->with('success', 'Cita creada correctamente para ' . $usuario->name . ' y su mascota ' . $mascota->nombre);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al crear la cita: ' . $e->getMessage()])->withInput();
        }
    }

    public function buscarMascotaPorCodigo(Request $request)
    {
        // Debug: verificar que el veterinario esté autenticado
        $veterinario = auth()->guard('veterinarios')->user();
        \Log::info('Veterinario autenticado:', [
            'veterinario_id' => $veterinario ? $veterinario->id : null,
            'veterinario_nombre' => $veterinario ? $veterinario->nombre : null,
        ]);

        $request->validate([
            'codigo' => 'required|string|max:16'
        ]);

        // Debug: verificar el código recibido
        \Log::info('Código recibido:', ['codigo' => $request->codigo]);

        // Buscar el código de acceso (no usado y no expirado)
        $accesoMascota = AccesoMascota::where('codigo', strtoupper($request->codigo))
            ->where('expires_at', '>', now()) // Verificar que no haya expirado
            ->where('usado', false) // Buscar códigos no usados
            ->with(['mascota.usuario'])
            ->first();

        // Debug: verificar si se encontró el acceso
        \Log::info('Acceso mascota encontrado:', [
            'encontrado' => $accesoMascota ? true : false,
            'codigo_buscado' => strtoupper($request->codigo)
        ]);

        if (!$accesoMascota) {
            return response()->json([
                'success' => false,
                'message' => 'Código no válido, expirado o ya usado'
            ], 404);
        }

        // Debug: verificar que los datos estén correctos
        \Log::info('Datos de mascota encontrada:', [
            'mascota_id' => $accesoMascota->mascota->id,
            'mascota_nombre' => $accesoMascota->mascota->nombre,
            'usuario_id' => $accesoMascota->mascota->usuario->id,
            'usuario_name' => $accesoMascota->mascota->usuario->name,
            'usuario_email' => $accesoMascota->mascota->usuario->email,
        ]);

        return response()->json([
            'success' => true,
            'mascota' => [
                'id' => $accesoMascota->mascota->id,
                'nombre' => $accesoMascota->mascota->nombre,
                'usuario' => [
                    'id' => $accesoMascota->mascota->usuario->id,
                    'name' => $accesoMascota->mascota->usuario->name,
                    'email' => $accesoMascota->mascota->usuario->email,
                ]
            ]
        ]);
    }

    public function obtenerMascotasUsuario(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:users,id'
        ]);

        $mascotas = Mascota::where('user_id', $request->usuario_id)
            ->select('id', 'nombre', 'especie')
            ->get();

        return response()->json([
            'success' => true,
            'mascotas' => $mascotas
        ]);
    }

    /**
     * Show the form for editing the specified cita.
     */
    public function edit($hashid)
    {
        $veterinario = auth()->guard('veterinarios')->user();
        
        // Resolver la cita por hashid
        $ids = Hashids::decode($hashid);
        if (count($ids) === 0) {
            abort(404);
        }
        $cita = Recordatorio::findOrFail($ids[0]);
        
        // Verificar que la cita sea una cita (es_cita = true)
        if (!$cita->es_cita) {
            abort(404, 'No se encontró la cita especificada.');
        }
        
        // Verificar que el veterinario tenga acceso a esta cita
        // (por ahora permitimos que cualquier veterinario edite cualquier cita)
        // En el futuro se puede agregar una relación específica
        
        $mascota = $cita->mascota;
        $usuario = $mascota->usuario;
        
        return view('citas.edit', compact('cita', 'mascota', 'usuario'));
    }

    /**
     * Update the specified cita in storage.
     */
    public function update(Request $request, $hashid)
    {
        $veterinario = auth()->guard('veterinarios')->user();
        
        // Resolver la cita por hashid
        $ids = Hashids::decode($hashid);
        if (count($ids) === 0) {
            abort(404);
        }
        $cita = Recordatorio::findOrFail($ids[0]);
        
        // Verificar que la cita sea una cita (es_cita = true)
        if (!$cita->es_cita) {
            abort(404, 'No se encontró la cita especificada.');
        }
        
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'fecha' => 'required|date|after_or_equal:now',
            'descripcion' => 'nullable|string',
        ], [
            'fecha.after_or_equal' => 'La fecha y hora de la cita no puede ser anterior al momento actual.',
        ]);

        try {
            $cita->update([
                'titulo' => $validated['titulo'],
                'fecha' => $validated['fecha'],
                'descripcion' => $validated['descripcion'],
            ]);

            return redirect()->route('veterinarios.perfil', $veterinario->hashid)
                ->with('success', 'Cita actualizada correctamente.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al actualizar la cita: ' . $e->getMessage()])->withInput();
        }
    }
}
