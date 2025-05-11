<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class ActualizarUltimaConexion
{
    public function handle(Login $event): void
    {
        $event->user->update(['ultima_conexion' => now()]);
        $actualizado = $event->user->fresh();

    }
}
