<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactoSoporte;

class SoporteController extends Controller
{
    public function mostrarFormulario()
    {
        return view('soporte.contacto');
    }

    public function enviarMensaje(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string|max:2000',
        ]);

        try {
            // Enviar el correo
            Mail::to('soporte@tfgwallet.com')->send(new ContactoSoporte($request->all()));

            return redirect()->back()->with('success', 'Mensaje enviado correctamente. Nos pondremos en contacto contigo pronto.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al enviar el mensaje. Por favor, inténtalo de nuevo.');
        }
    }
} 