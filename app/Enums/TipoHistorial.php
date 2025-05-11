<?php
namespace App\Enums;

enum TipoHistorial: string
{
case Consulta = 'Consulta';
case Vacunacion = 'Vacunación';
case Desparasitacion = 'Desparasitación';
case Cirugia = 'Cirugía';
case Analisis = 'Análisis';
case Diagnostico = 'Diagnóstico';
case Tratamiento = 'Tratamiento';
case Alta = 'Alta';
case Observacion = 'Observación';
case Otro = 'Otro';
}
