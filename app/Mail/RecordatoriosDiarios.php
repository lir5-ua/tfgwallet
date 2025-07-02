<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecordatoriosDiarios extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $recordatoriosHoy;
    public $recordatoriosManana;
    public $recordatoriosPasado;

    /**
     * Create a new message instance.
     */
    public function __construct($usuario, $recordatoriosHoy, $recordatoriosManana, $recordatoriosPasado)
    {
        $this->usuario = $usuario;
        $this->recordatoriosHoy = $recordatoriosHoy;
        $this->recordatoriosManana = $recordatoriosManana;
        $this->recordatoriosPasado = $recordatoriosPasado;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Tus recordatorios de mascotas')->view('emails.recordatorios-diarios');
    }
} 