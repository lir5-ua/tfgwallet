<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Mensaje de Soporte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }
        .field {
            margin-bottom: 15px;
        }
        .field-label {
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        .field-value {
            background: white;
            padding: 10px;
            border-radius: 4px;
            border-left: 4px solid #667eea;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🐾 Nuevo Mensaje de Soporte</h1>
        <p>TFG Wallet - Sistema de Gestión de Mascotas</p>
    </div>
    
    <div class="content">
        <p>Se ha recibido un nuevo mensaje de contacto a través del formulario de soporte:</p>
        
        <div class="field">
            <div class="field-label">Nombre:</div>
            <div class="field-value">{{ $nombre }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">Email:</div>
            <div class="field-value">{{ $email }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">Asunto:</div>
            <div class="field-value">{{ $asunto }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">Mensaje:</div>
            <div class="field-value">{{ $mensaje }}</div>
        </div>
        
        <div class="footer">
            <p>Este mensaje fue enviado desde el formulario de contacto de TFG Wallet</p>
            <p>Fecha: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html> 