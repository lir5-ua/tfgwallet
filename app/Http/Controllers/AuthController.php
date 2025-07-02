<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);
        
        $user = User::where('name', $request->name)->first();
        if ($user && is_null($user->ultima_conexion)) {
            return back()->withErrors([
                'name' => 'Tu cuenta no está verificada. Por favor, revisa tu correo electrónico para el enlace de verificación.',
            ])->onlyInput('name');
        }
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $authenticatedUser = Auth::user();
            $authenticatedUser->ultima_conexion = now(); // Actualiza a la hora actual del login
            $authenticatedUser->save();

            return redirect()->route('usuarios.show', $authenticatedUser); // Redirige al usuario autenticado.
        }
        
        return back()->withErrors([
            'name' => 'Las credenciales no son correctas.',
        ])->onlyInput('name');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
