<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            // Aquí actualizamos la fecha de última conexión
            \App\Listeners\ActualizarUltimaConexion::class,
        ],
        \Illuminate\Auth\Events\Verified::class => [ 
            \App\Listeners\UpdateUltimaConexionOnVerified::class, 
        ],
    ];

    public function boot(): void
    {
        //
    }
}
