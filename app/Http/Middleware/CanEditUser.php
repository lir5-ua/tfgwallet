<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanEditUser
{
    public function handle(Request $request, Closure $next)
    {
        $authUser = Auth::user();
        $usuario = $request->route('usuario'); // ahora sabemos que es un objeto User

        if ($authUser && ($authUser->id == $usuario->id || $authUser->is_admin)) {
            return $next($request);
        }

        abort(403, 'No tienes permiso para editar este perfil.');
    }
}
