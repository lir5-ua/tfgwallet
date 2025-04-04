@extends('layouts.gestion')

@section('content')
<div class="container">
    <h2>Crear nuevo usuario</h2>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf

        <label>Nombre:</label><br>
        <input type="text" name="name" value="{{ old('name') }}"><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="{{ old('email') }}"><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="password"><br><br>

        <label>Confirmar contraseña:</label><br>
        <input type="password" name="password_confirmation"><br><br>

        <button type="submit">💾 Guardar</button>
    </form>

    <br>
    <a href="{{ route('usuarios.index') }}">← Volver a usuarios</a>
</div>
@endsection
