<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\Especie;
use App\Enums\Sexo;
use App\Models\Mascota;
use App\Models\User;
use Faker\Factory as Faker;

class MascotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('es_ES');
        
        // Obtener todos los usuarios disponibles
        $usuarios = User::all();
        
        if ($usuarios->isEmpty()) {
            // Si no hay usuarios, crear uno por defecto
            $usuario = User::factory()->create([
                'name' => 'Usuario Demo',
                'email' => 'demo@example.com',
                'password' => bcrypt('123'),
                'foto' => 'perroGodlen.jpg',
            ]);
            $usuarios = collect([$usuario]);
        }

        // Crear 30 mascotas
        for ($i = 0; $i < 30; $i++) {
            $especie = $faker->randomElement(Especie::cases());
            $razas = $especie->razas();
            $raza = $faker->randomElement($razas);
            
            // Generar fecha de nacimiento (entre 1 y 15 años atrás)
            $fechaNacimiento = $faker->optional(0.8)->dateTimeBetween('-15 years', '-1 year');
            
            // Generar notas realistas según la especie
            $notas = $this->generarNotas($especie, $faker);
            
            Mascota::create([
                'nombre' => $faker->firstName(),
                'user_id' => $usuarios->random()->id,
                'especie' => $especie,
                'raza' => $raza,
                'fecha_nacimiento' => $fechaNacimiento,
                'sexo' => $faker->randomElement(Sexo::cases()),
                'notas' => $notas,
                'imagen' => 'mascotas/default_' . strtolower($especie->name) . '.jpg',
            ]);
        }
    }
    
    /**
     * Generar notas realistas según la especie
     */
    private function generarNotas(Especie $especie, $faker): string
    {
        $notas = [];
        
        switch ($especie) {
            case Especie::Perro:
                $notas[] = $faker->randomElement([
                    'Muy juguetón y activo',
                    'Tranquilo y cariñoso',
                    'Excelente con niños',
                    'Necesita mucho ejercicio',
                    'Muy inteligente y obediente',
                    'Protector de la familia',
                    'Le encanta pasear',
                    'Muy sociable con otros perros'
                ]);
                break;
                
            case Especie::Gato:
                $notas[] = $faker->randomElement([
                    'Independiente y curioso',
                    'Muy cariñoso y mimoso',
                    'Le gusta dormir en lugares altos',
                    'Excelente cazador',
                    'Muy limpio y aseado',
                    'Juguetón y activo',
                    'Tranquilo y relajado',
                    'Le encanta el sol'
                ]);
                break;
                
            case Especie::Ave:
                $notas[] = $faker->randomElement([
                    'Muy cantarina y alegre',
                    'Inteligente y sociable',
                    'Le gusta la compañía',
                    'Excelente imitadora',
                    'Muy activa y juguetona',
                    'Necesita estimulación mental',
                    'Muy cariñosa con su dueño'
                ]);
                break;
                
            case Especie::Pez:
                $notas[] = $faker->randomElement([
                    'Muy colorido y llamativo',
                    'Tranquilo y pacífico',
                    'Excelente para principiantes',
                    'Muy resistente',
                    'Le gusta nadar en grupo',
                    'Muy activo en el acuario'
                ]);
                break;
                
            default:
                $notas[] = $faker->sentence(6);
        }
        
        // Agregar información adicional ocasional
        if ($faker->boolean(30)) {
            $notas[] = $faker->randomElement([
                'Vacunado al día',
                'Esterilizado',
                'Microchip implantado',
                'Alergia a ciertos alimentos',
                'Necesita dieta especial',
                'Muy saludable'
            ]);
        }
        
        return implode('. ', $notas);
    }
}
