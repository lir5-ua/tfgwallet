<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SeparateSessionGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Determinar qué guard se está usando basado en la ruta
        $guard = $this->determineGuard($request);
        
        if ($guard) {
            // Configurar la sesión específica para este guard
            $this->configureSessionForGuard($request, $guard);
        }

        return $next($request);
    }

    /**
     * Determinar qué guard usar basado en la ruta actual
     */
    private function determineGuard(Request $request): ?string
    {
        $path = $request->path();
        
        // Rutas de veterinarios
        if (str_starts_with($path, 'veterinarios') || 
            str_starts_with($path, 'citas') ||
            str_starts_with($path, 'acceso/historial')) {
            return 'veterinarios';
        }
        
        // Rutas de usuarios normales
        if (str_starts_with($path, 'usuarios') ||
            str_starts_with($path, 'mascotas') ||
            str_starts_with($path, 'recordatorios') ||
            str_starts_with($path, 'historial') ||
            $path === '' ||
            $path === 'login' ||
            $path === 'register' ||
            $path === 'logout') {
            return 'web';
        }
        
        return null;
    }

    /**
     * Configurar la sesión específica para el guard
     */
    private function configureSessionForGuard(Request $request, string $guard): void
    {
        // Verificar si ya hay una sesión activa para este guard
        $currentGuard = Session::get('guard');
        
        // Si el guard actual es diferente, limpiar la sesión anterior
        if ($currentGuard && $currentGuard !== $guard) {
            $this->clearOtherGuardSession($currentGuard);
        }
        
        // Configurar el guard actual
        Session::put('guard', $guard);
    }

    /**
     * Limpiar sesión de otro guard para evitar conflictos
     */
    private function clearOtherGuardSession(string $otherGuard): void
    {
        // Cerrar sesión del otro guard
        Auth::guard($otherGuard)->logout();
        
        // Limpiar datos específicos del otro guard
        Session::forget("auth.{$otherGuard}");
        
        if ($otherGuard === 'veterinarios') {
            Session::forget('veterinario_id');
        } elseif ($otherGuard === 'web') {
            Session::forget('user_id');
        }
    }
}
