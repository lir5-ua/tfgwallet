<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified; // El evento que escuchamos
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Carbon\Carbon; // Para obtener la fecha y hora actual

class UpdateUltimaConexionOnVerified
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        // Cuando el usuario ha verificado su email, actualiza ultima_conexion
        $user = $event->user;

        // Solo actualiza si es null para la verificación inicial
        if (is_null($user->ultima_conexion)) {
            $user->ultima_conexion = Carbon::now();
            $user->save();
        }
    }
}