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
        // Obtener un veterinario real o crear uno de prueba
        $veterinario = \App\Models\Veterinario::first();
        if (!$veterinario) {
            $veterinario = \App\Models\Veterinario::create([
                'nombre' => 'Veterinario Demo',
                'email' => 'vetdemo@example.com',
                'numero_colegiado' => 'VET12345',
                'password' => bcrypt('123'),
            ]);
        }
        HistorialMedico::create([
            'mascota_id' => 1,
            'fecha' => now()->subDays(10),
            'tipo' => 'Vacunación',
            'descripcion' => 'Vacuna mala',
            'veterinario_id' => $veterinario->id,
        ]);
    }
}
