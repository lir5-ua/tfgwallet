<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PetWallet - Tu cartilla digital de mascotas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/js/app.js')
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
        }
        .hero {
            background: url('/images/fondo-mascotas.jpg') no-repeat center center;
            background-size: cover;
            color: white;
            padding: 100px 20px;
            text-align: center;
        }
        .btn {
            background-color: #38b2ac;
            color: white;
            padding: 12px 25px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="hero">
        <h1>🦴 Bienvenido a PetWallet</h1>
        <p>Guarda el historial médico de tus mascotas en un solo lugar, accesible desde cualquier dispositivo.</p>
        <a href="{{ url('/login') }}" class="btn">Empezar ahora</a>

    </div>

        <div class="bg-blue-500 text-white p-4 mt-4 rounded">
            Si ves esto en azul con texto blanco, Tailwind está funcionando 🎉
        </div>

    <section style="padding: 40px; text-align: center;">
        <h2>🐶 ¿Qué puedes hacer?</h2>
        <ul style="list-style: none; padding: 0; max-width: 600px; margin: auto;">
            <li>✅ Registrar tus mascotas</li>
            <li>✅ Guardar visitas al veterinario</li>
            <li>✅ Consultar tratamientos anteriores</li>
            <li>✅ Conectar con clínicas recomendadas</li>
        </ul>
    </section>
<div class="bg-red-500 text-black p-4">
    Tailwind funciona 🎉
</div>

    <footer style="text-align: center; padding: 20px; background-color: #f3f3f3;">
        &copy; {{ date('Y') }} PetWallet. Todos los derechos reservados.
    </footer>
</body>
</html>
