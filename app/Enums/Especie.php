<?php
// app/Enums/Especie.php
namespace App\Enums;

enum Especie: string
{
    case Perro = 'perro';
    case Gato = 'gato';
    case Ave = 'ave';
    case Pez = 'pez';

    public function razas(): array
    {
        return match ($this) {
            Especie::Perro => ['Labrador', 'Golden Retriever', 'Bulldog'],
            Especie::Gato => ['Siamés', 'Persa', 'Maine Coon'],
            Especie::Ave => ['Canario', 'Periquito'],
            Especie::Pez => ['Betta', 'Guppy'],
        };
    }
}
