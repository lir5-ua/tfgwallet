<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUltimaConexion
{
    public function handle(Request $request, Closure $next): Response
    {
        // Si el usuario está autenticado y ultima_conexion es null, lo desautentica
        if (Auth::check() && is_null(Auth::user()->ultima_conexion)) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'name' => 'Tu cuenta no está verificada. Por favor, revisa tu correo electrónico.',
            ]);
        }

        return $next($request);
    }
}