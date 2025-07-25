<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Veterinario;
use Illuminate\Support\Facades\Hash;

class VeterinarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Veterinario::create([
            'nombre' => 'Dra. Ana López',
            'email' => 'ana.vet@example.com',
            'numero_colegiado' => 'VET123456',
            'password' => Hash::make('password123'),
            'telefono' => '600123456',
            'direccion' => 'Calle Salud 10, Madrid',
        ]);
        Veterinario::create([
            'nombre' => 'Dr. Carlos Pérez',
            'email' => 'carlos.vet@example.com',
            'numero_colegiado' => 'VET654321',
            'password' => Hash::make('password456'),
            'telefono' => '600654321',
            'direccion' => 'Avenida Mascotas 22, Barcelona',
        ]);
        Veterinario::create([
            'nombre' => 'Dr. Carlos Pérez',
            'email' => 'carlos@example.com',
            'numero_colegiado' => 'VET654221',
            'password' => Hash::make('123'),
            'telefono' => '600654221',
            'direccion' => 'Avenida Mascotas 22, Barcelona',
        ]);
    }
}
