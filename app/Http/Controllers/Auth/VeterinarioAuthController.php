<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Veterinario;
use Illuminate\Support\Facades\Hash;

class VeterinarioAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('veterinarios.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $veterinario = Veterinario::where('email', $request->email)->first();
        if ($veterinario && Hash::check($request->password, $veterinario->password)) {
            // Autenticación manual para veterinario
            session(['veterinario_id' => $veterinario->id]);
            auth('veterinarios')->login($veterinario, true); // Usar guard de veterinarios
            return redirect()->route('veterinarios.perfil', $veterinario->hashid);
        }
        return back()->withErrors(['email' => 'Credenciales incorrectas'])->withInput();
    }

    public function logout(Request $request)
    {
        auth('veterinarios')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('veterinarios.login');
    }
}
