<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuarios específicos con la misma foto
        User::factory()->create([
            'name' => 'Manolo',
            'email' => 'manolo@example.com',
            'password' => bcrypt('123'),
            'is_admin' => true,
            'foto' => 'perroGodlen.jpg',
            'ultima_conexion' => now(),
        ]);

        User::factory()->create([
            'name' => 'Paco',
            'email' => 'paco@example.com',
            'password' => bcrypt('123'),
            'is_admin' => false,
            'foto' => 'perroGodlen.jpg',
            'ultima_conexion' => now(),
        ]);

        // Crear usuarios adicionales con la misma foto
        for ($i = 0; $i < 10; $i++) {
            User::factory()->create([
                'foto' => 'perroGodlen.jpg',
                'ultima_conexion' => now(),
            ]);
        }
    }
}
