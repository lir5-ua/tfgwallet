<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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
            // Configurar sesión específica para usuarios web
            $this->configureWebSession($request, $user);
            
            $request->session()->regenerate();
            $authenticatedUser = Auth::user();
            $authenticatedUser->ultima_conexion = now();
            $authenticatedUser->save();

            return redirect()->route('usuarios.show', $authenticatedUser);
        }
        
        return back()->withErrors([
            'name' => 'Las credenciales no son correctas.',
        ])->onlyInput('name');
    }

    public function logout(Request $request)
    {
        // Limpiar sesión específica de usuarios web
        $this->clearWebSession($request);
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Configurar sesión específica para usuarios web
     */
    private function configureWebSession(Request $request, User $user): void
    {
        // Guardar datos del usuario en la sesión específica
        Session::put('user_id', $user->id);
        
        // Configurar cookie de sesión específica para usuarios web
        $request->session()->put('guard', 'web');
    }

    /**
     * Limpiar sesión específica de usuarios web
     */
    private function clearWebSession(Request $request): void
    {
        Session::forget('user_id');
        Session::forget('guard');
    }
}
