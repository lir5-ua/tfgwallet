<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AsignarFotosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los usuarios que no tienen foto asignada o que tienen una foto diferente
        $usuariosSinFoto = User::whereNull('foto')
            ->orWhere('foto', '')
            ->orWhere('foto', '!=', 'perroGodlen.jpg')
            ->get();

        foreach ($usuariosSinFoto as $usuario) {
            $usuario->update([
                'foto' => 'perroGodlen.jpg'
            ]);
        }

        $this->command->info("Se han asignado la foto 'perroGodlen.jpg' a {$usuariosSinFoto->count()} usuarios.");
    }
} 