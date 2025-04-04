<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\Especie;
use App\Enums\Sexo;
use App\Models\Mascota;
class MascotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Mascota::create([
        'nombre' => 'Toby',
        'user_id' =>1,
        'especie' => Especie::Perro,
        'raza' => 'Labrador',
        'fecha_nacimiento' =>null,
        'sexo' => Sexo::Macho,
        'notas' => 'pequeño',]);

    }
}
