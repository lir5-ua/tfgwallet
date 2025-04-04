<?php

namespace Database\Factories;

use App\Models\Mascota;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Enums\Especie; // si usas enum para especie
use App\Enums\Sexo;    // si usas enum para sexo
use Carbon\Carbon;

class MascotaFactory extends Factory
{
    protected $model = Mascota::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->firstName,
            'especie' => $this->faker->randomElement(Especie::cases())->value,
            'raza' => $this->faker->word,
            'fecha_nacimiento' => $this->faker->date('Y-m-d', '-1 year'),
           'sexo' => $this->faker->randomElement(['M', 'H']),
            'notas' => $this->faker->optional()->sentence,
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(), // si no hay usuarios, crea uno
        ];
    }
}
