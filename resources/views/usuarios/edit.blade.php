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

    <form action="{{ route('usuarios.update', $usuario) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label for="imagen">Foto:</label><br>
        <input type="file" name="foto" id="foto" accept="image/*"><br><br>

        <label>Nombre:</label><br>
        <input type="text" name="name" value="{{ old('name', $usuario->name) }}"><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="{{ old('email', $usuario->email) }}"><br><br>

        <label>Contraseña nueva (opcional):</label><br>
        <input type="password" name="password"><br><br>

        <label>Confirmar nueva contraseña:</label><br>
        <input type="password" name="password_confirmation"><br><br>

        <label>Es administrador:</label><br>
                <input type="checkbox" name="esAdmin" {{ $usuario->is_admin ? 'checked' : '' }}><br><br>

        <button type="submit">💾 Guardar cambios</button>
    </form>

    <br>
    <a href="{{ url()->previous() }}" class="btn btn-secondary">Volver</a>
</div>
@endsection
