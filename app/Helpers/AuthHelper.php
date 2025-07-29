<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthHelper
{
    /**
     * Verificar si el usuario está autenticado en cualquier guard
     */
    public static function isAuthenticated(): bool
    {
        return Auth::guard('web')->check() || Auth::guard('veterinarios')->check();
    }

    /**
     * Obtener el usuario autenticado actual (de cualquier guard)
     */
    public static function getCurrentUser()
    {
        if (Auth::guard('web')->check()) {
            return Auth::guard('web')->user();
        }
        
        if (Auth::guard('veterinarios')->check()) {
            return Auth::guard('veterinarios')->user();
        }
        
        return null;
    }

    /**
     * Obtener el guard actual
     */
    public static function getCurrentGuard(): ?string
    {
        if (Auth::guard('web')->check()) {
            return 'web';
        }
        
        if (Auth::guard('veterinarios')->check()) {
            return 'veterinarios';
        }
        
        return null;
    }

    /**
     * Verificar si el usuario es un veterinario
     */
    public static function isVeterinario(): bool
    {
        return Auth::guard('veterinarios')->check();
    }

    /**
     * Verificar si el usuario es un usuario normal
     */
    public static function isUser(): bool
    {
        return Auth::guard('web')->check();
    }

    /**
     * Obtener el ID del usuario autenticado
     */
    public static function getCurrentUserId(): ?int
    {
        $user = self::getCurrentUser();
        return $user ? $user->id : null;
    }

    /**
     * Cerrar sesión de todos los guards
     */
    public static function logoutAll(): void
    {
        Auth::guard('web')->logout();
        Auth::guard('veterinarios')->logout();
        
        Session::forget('user_id');
        Session::forget('veterinario_id');
        Session::forget('guard');
    }
} 