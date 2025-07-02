<div style="background: linear-gradient(135deg, #fdf6e3 0%, #ffe0b2 100%); padding: 32px 16px; border-radius: 18px; font-family: 'Segoe UI', Arial, sans-serif; color: #333; max-width: 600px; margin: auto; border: 2px solid #ffb74d;">
    <h2 style="color: #ff9800; text-align: center; font-size: 2em; margin-bottom: 0.5em;">🐾 ¡Hola {{ $usuario->name }}!</h2>
    <p style="font-size: 1.1em; text-align: center; margin-bottom: 1.5em;">Estos son tus <strong style='color:#ff9800;'>recordatorios de mascotas</strong>:</p>

    @if($recordatoriosHoy->isNotEmpty())
        <h3 style="color: #43a047; margin-top: 1.5em;">🟢 Para hoy:</h3>
        <ul style="padding-left: 1.2em;">
            @foreach($recordatoriosHoy as $rec)
                <li style="margin-bottom: 0.5em; font-size: 1.05em;">
                    <span style="font-weight: bold; color: #388e3c;">🐶 {{ $rec->titulo }}</span> <span style="color: #6d4c41;">({{ $rec->mascota->nombre }})</span>: <span style="color: #333;">{{ $rec->descripcion }}</span>
                </li>
            @endforeach
        </ul>
    @endif

    @if($recordatoriosManana->isNotEmpty())
        <h3 style="color: #1976d2; margin-top: 1.5em;">🔵 Para mañana:</h3>
        <ul style="padding-left: 1.2em;">
            @foreach($recordatoriosManana as $rec)
                <li style="margin-bottom: 0.5em; font-size: 1.05em;">
                    <span style="font-weight: bold; color: #1976d2;">🐱 {{ $rec->titulo }}</span> <span style="color: #6d4c41;">({{ $rec->mascota->nombre }})</span>: <span style="color: #333;">{{ $rec->descripcion }}</span>
                </li>
            @endforeach
        </ul>
    @endif

    @if($recordatoriosPasado->isNotEmpty())
        <h3 style="color: #fbc02d; margin-top: 1.5em;">🟡 Para pasado mañana:</h3>
        <ul style="padding-left: 1.2em;">
            @foreach($recordatoriosPasado as $rec)
                <li style="margin-bottom: 0.5em; font-size: 1.05em;">
                    <span style="font-weight: bold; color: #fbc02d;">🐾 {{ $rec->titulo }}</span> <span style="color: #6d4c41;">({{ $rec->mascota->nombre }})</span>: <span style="color: #333;">{{ $rec->descripcion }}</span>
                </li>
            @endforeach
        </ul>
    @endif

    @if($recordatoriosHoy->isEmpty() && $recordatoriosManana->isEmpty() && $recordatoriosPasado->isEmpty())
        <p style="text-align: center; color: #757575; font-size: 1.1em; margin-top: 2em;">🎉 No tienes recordatorios próximos. ¡Disfruta tu día!</p>
    @endif

    <div style="margin-top: 2.5em; text-align: center;">
        <p style="font-size: 1.15em; color: #ff9800; font-weight: bold;">¡Que tengas un gran día! ☀️🐕🐈</p>
        <p style="font-size: 0.95em; color: #bdbdbd; margin-top: 1em;">PetWallet - Tu asistente para el cuidado de tus mascotas</p>
    </div>
</div> 