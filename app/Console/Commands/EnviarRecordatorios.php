<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Recordatorio;

class EnviarRecordatorios extends Command
{
    protected $signature = 'recordatorios:enviar';
    protected $description = 'Envía recordatorios por email 1 día antes de la fecha';

    public function handle()
    {
        $manana = now()->addDay()->toDateString();

        // Carga recordatorios con sus mascotas y usuarios
        $recordatorios = Recordatorio::where('fecha', $manana)
            ->with('mascota.usuario')
            ->get();

        foreach ($recordatorios as $recordatorio) {
            $mascota = $recordatorio->mascota;
            $usuario = $mascota?->usuario;

            if (!$usuario || !$usuario->email) {
                $this->warn("Mascota sin usuario válido para el recordatorio ID {$recordatorio->id}");
                continue;
            }

            $email = $usuario->email;
            $name = $usuario->name;

            // SOLO enviar al correo autorizado por Resend en modo test
            if ($email !== 'lir5@gcloud.ua.es') {
                $this->warn("Correo omitido: $email no está permitido en modo test.");
                continue;
            }

            // Enviar el correo
            $response = Http::withToken(env('RESEND_API_KEY'))->post('https://api.resend.com/emails', [
                'from' => 'onboarding@resend.dev',
                'to' => $email,
                'subject' => '⏰ Recordatorio para tu mascota',
                'html' => "<p>Hola {$name},<br>Recuerda que mañana tienes: <strong>{$recordatorio->titulo}</strong></p>",
            ]);

            if ($response->successful()) {
                $this->info("Correo enviado a $email");
            } else {
                $this->error("Error al enviar a $email: " . $response->body());
            }
        }
    }
}
