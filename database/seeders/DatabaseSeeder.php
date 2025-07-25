<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mascota;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         User::factory(10)->create();
         Mascota::factory(30)->create();
        $this->call([
            UsuarioSeeder::class,
            MascotaSeeder::class,
            HistorialMedicoSeeder::class,
            AsignarFotosSeeder::class,
            VeterinarioSeeder::class,
        ]);

    }
}
