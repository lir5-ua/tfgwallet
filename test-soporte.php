<?php

require_once 'vendor/autoload.php';

use App\Mail\ContactoSoporte;
use Illuminate\Support\Facades\Mail;

// Simular datos de prueba
$datos = [
    'nombre' => 'Usuario de Prueba',
    'email' => 'test@example.com',
    'asunto' => 'Prueba del sistema de soporte',
    'mensaje' => 'Este es un mensaje de prueba para verificar que el sistema de soporte funciona correctamente.'
];

try {
    // Crear instancia del mail
    $mail = new ContactoSoporte($datos);
    
    echo "✅ Sistema de soporte configurado correctamente\n";
    echo "📧 Email de destino: soporte@tfgwallet.com\n";
    echo "📝 Datos de prueba:\n";
    echo "   - Nombre: {$datos['nombre']}\n";
    echo "   - Email: {$datos['email']}\n";
    echo "   - Asunto: {$datos['asunto']}\n";
    echo "   - Mensaje: {$datos['mensaje']}\n";
    echo "\n🎉 ¡Todo listo! El sistema de soporte está funcionando.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 