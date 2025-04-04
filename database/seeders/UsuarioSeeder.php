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
        User::factory()->create([
            'name' => 'Manolo',
            'email' => 'manolo@example.com',
            'password' => bcrypt('123'),
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Paco',
            'email' => 'paco@example.com',
            'password' => bcrypt('123'),
            'is_admin' => false,
        ]);
    }
}
