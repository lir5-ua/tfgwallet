<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactoSoporte extends Mailable
{
    use Queueable, SerializesModels;

    public $datos;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($datos)
    {
        $this->datos = $datos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nuevo mensaje de soporte: ' . $this->datos['asunto'])
                    ->view('emails.contacto-soporte')
                    ->with([
                        'nombre' => $this->datos['nombre'],
                        'email' => $this->datos['email'],
                        'asunto' => $this->datos['asunto'],
                        'mensaje' => $this->datos['mensaje'],
                    ]);
    }
} 