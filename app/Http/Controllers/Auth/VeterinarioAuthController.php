<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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
            // Configurar sesión específica para veterinarios
            $this->configureVeterinarioSession($request, $veterinario);
            
            // Autenticación manual para veterinario
            auth('veterinarios')->login($veterinario, true);
            
            return redirect()->route('veterinarios.perfil', $veterinario->hashid);
        }
        return back()->withErrors(['email' => 'Credenciales incorrectas'])->withInput();
    }

    public function logout(Request $request)
    {
        // Limpiar sesión específica de veterinarios
        $this->clearVeterinarioSession($request);
        
        auth('veterinarios')->logout();
        
        return redirect()->route('veterinarios.login');
    }

    /**
     * Configurar sesión específica para veterinarios
     */
    private function configureVeterinarioSession(Request $request, Veterinario $veterinario): void
    {
        // Guardar datos del veterinario en la sesión específica
        Session::put('veterinario_id', $veterinario->id);
        
        // Configurar cookie de sesión específica para veterinarios
        $request->session()->put('guard', 'veterinarios');
    }

    /**
     * Limpiar sesión específica de veterinarios
     */
    private function clearVeterinarioSession(Request $request): void
    {
        Session::forget('veterinario_id');
        Session::forget('guard');
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
