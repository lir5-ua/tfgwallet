<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HistorialMedico;
class HistorialMedicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HistorialMedico::create([
        'mascota_id' => 1,
        'fecha' => now()->subDays(10),
        'tipo' => 'Vacunación',
        'descripcion' => 'Vacuna mala',
        'veterinario' => 'Clinica Salud',]);
    }
}
