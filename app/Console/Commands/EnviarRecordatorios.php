<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Recordatorio;
use App\Mail\RecordatoriosDiarios;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class EnviarRecordatorios extends Command
{
    protected $signature = 'recordatorios:enviar';
    protected $description = 'Envía los recordatorios diarios por email a los usuarios que lo tengan activado';

    public function handle()
    {
        $hoy = Carbon::today()->toDateString();
        $manana = Carbon::tomorrow()->toDateString();
        $pasado = Carbon::today()->addDays(2)->toDateString();

        $usuarios = User::where('notificar_email', true)->get();

        foreach ($usuarios as $usuario) {
            $recordatoriosHoy = Recordatorio::whereHas('mascota', function($q) use ($usuario) {
                $q->where('user_id', $usuario->id);
            })->where('fecha', $hoy)->where('realizado', false)->get();

            $recordatoriosManana = Recordatorio::whereHas('mascota', function($q) use ($usuario) {
                $q->where('user_id', $usuario->id);
            })->where('fecha', $manana)->where('realizado', false)->get();

            $recordatoriosPasado = Recordatorio::whereHas('mascota', function($q) use ($usuario) {
                $q->where('user_id', $usuario->id);
            })->where('fecha', $pasado)->where('realizado', false)->get();

            if ($recordatoriosHoy->isNotEmpty() || $recordatoriosManana->isNotEmpty() || $recordatoriosPasado->isNotEmpty()) {
                Mail::to($usuario->email)->send(new RecordatoriosDiarios($usuario, $recordatoriosHoy, $recordatoriosManana, $recordatoriosPasado));
            }
        }
        $this->info('Recordatorios enviados correctamente.');
    }
}
