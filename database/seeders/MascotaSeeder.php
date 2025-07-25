<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\Especie;
use App\Enums\Sexo;
use App\Models\Mascota;
use App\Models\User;
use Faker\Factory as Faker;
use App\Models\Recordatorio;
use App\Models\HistorialMedico;

class MascotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('es_ES');
        
        // Obtener todos los usuarios disponibles que NO son administradores
        $usuarios = User::where('is_admin', false)->get();
        
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

        // Por cada usuario, crear 10 mascotas
        foreach ($usuarios as $usuario) {
            for ($i = 0; $i < 10; $i++) {
                $especie = $faker->randomElement(Especie::cases());
                $razas = $especie->razas();
                $raza = $faker->randomElement($razas);
                $fechaNacimiento = $faker->optional(0.8)->dateTimeBetween('-15 years', '-1 year');
                $notas = $this->generarNotas($especie, $faker);
                $mascota = Mascota::create([
                    'nombre' => $faker->firstName(),
                    'user_id' => $usuario->id,
                    'especie' => $especie,
                    'raza' => $raza,
                    'fecha_nacimiento' => $fechaNacimiento,
                    'sexo' => $faker->randomElement(Sexo::cases()),
                    'notas' => $notas,
                    'imagen' => 'mascotas/default_pet.jpg',
                ]);
                // 10 recordatorios mezclados
                for ($j = 0; $j < 10; $j++) {
                    Recordatorio::create([
                        'titulo' => $faker->sentence(3),
                        'fecha' => $faker->dateTimeBetween('-1 month', '+2 month'),
                        'descripcion' => $faker->sentence(8),
                        'realizado' => $j < 5 ? true : false, // 5 vistos, 5 no vistos
                        'mascota_id' => $mascota->id,
                    ]);
                }
                // 10 entradas médicas con descripciones realistas
                $tipos = ['Vacunación', 'Consulta', 'Desparasitación', 'Cirugía', 'Revisión'];
                foreach (range(1, 10) as $k) {
                    $tipo = $faker->randomElement($tipos);
                    $descripcion = match($tipo) {
                        'Vacunación' => 'Se administró la vacuna correspondiente. No se observaron reacciones adversas.',
                        'Consulta' => 'Consulta general. El animal presenta buen estado de salud.',
                        'Desparasitación' => 'Desparasitación interna y externa realizada con éxito.',
                        'Cirugía' => 'Intervención quirúrgica realizada sin complicaciones. Se recomienda reposo.',
                        'Revisión' => 'Revisión rutinaria. No se detectaron anomalías.',
                        default => $faker->sentence(10),
                    };
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
                        'mascota_id' => $mascota->id,
                        'fecha' => $faker->dateTimeBetween('-2 years', 'now'),
                        'tipo' => $tipo,
                        'descripcion' => $descripcion,
                        'veterinario_id' => $veterinario->id,
                    ]);
                }
            }
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
