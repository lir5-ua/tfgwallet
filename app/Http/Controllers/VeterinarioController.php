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
}
