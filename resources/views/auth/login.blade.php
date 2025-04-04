<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
</head>
<body>
    <h1>Iniciar Sesión</h1>

    @if($errors->any())
        <div style="color: red;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ url('/login') }}">
        @csrf
        <label for="name">Usuario</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" required><br><br>

        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" required><br><br>

        <button type="submit">Entrar</button>
    </form>
</body>
</html>
