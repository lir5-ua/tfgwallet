@extends('layouts.gestion')

@section('content')
<div class="container">
    <h2>Editar usuario: {{ $usuario->name }}</h2>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nombre:</label><br>
        <input type="text" name="name" value="{{ old('name', $usuario->name) }}"><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="{{ old('email', $usuario->email) }}"><br><br>

        <label>Contraseña nueva (opcional):</label><br>
        <input type="password" name="password"><br><br>

        <label>Confirmar nueva contraseña:</label><br>
        <input type="password" name="password_confirmation"><br><br>

        <button type="submit">💾 Guardar cambios</button>
    </form>

    <br>
    <a href="{{ route('usuarios.index') }}">← Volver a usuarios</a>
</div>
@endsection
