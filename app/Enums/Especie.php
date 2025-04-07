<?php
// app/Enums/Especie.php
namespace App\Enums;

enum Especie: string
{
    case Perro = 'perro';
    case Gato = 'gato';
    case Ave = 'ave';
    case Pez = 'pez';

    public function razas(): array
    {
        return match ($this) {

                Especie::Perro => [
                    'Labrador Retriever', 'Golden Retriever', 'Bulldog Inglés', 'Bulldog Francés', 'Pastor Alemán',
                    'Beagle', 'Chihuahua', 'Pug', 'Cocker Spaniel', 'Shih Tzu', 'Yorkshire Terrier',
                    'Dálmata', 'Border Collie', 'Husky Siberiano', 'Doberman', 'Rottweiler','Otro'
                ],
                Especie::Gato => [
                    'Siamés', 'Persa', 'Maine Coon', 'Bengala', 'Sphynx', 'Ragdoll', 'British Shorthair',
                    'Azul Ruso', 'Gato común europeo', 'Bombay', 'Angora Turco', 'Abisinio','Otro'
                ],
                Especie::Ave => [
                    'Canario', 'Periquito', 'Agaporni', 'Cacatúa', 'Loro Amazonas', 'Ninfa (Carolina)',
                    'Guacamayo', 'Jilguero', 'Diamante Mandarín', 'Pionus', 'Cotorra','Otro'
                ],
                Especie::Pez => [
                    'Betta', 'Guppy', 'Neón Tetra', 'Goldfish', 'Molly', 'Platy', 'Escalar', 'Pez Ángel',
                    'Pez Disco', 'Corydora', 'Barbo', 'Pez Gato','Otro'
                ],
                Especie::Roedor => [
                    'Hámster Sirio', 'Hámster Enano Ruso', 'Cobaya (Cuy)', 'Ratón doméstico', 'Rata',
                    'Gerbo', 'Chinchilla', 'Degú','Otro'
                ],
                Especie::Reptil => [
                    'Iguana', 'Gecko Leopardo', 'Dragón Barbudo', 'Tortuga de tierra', 'Tortuga de agua',
                    'Serpiente del maíz', 'Boa constrictor', 'Camaleón','Otro'
                ],
                Especie::Anfibio => [
                    'Rana pacman', 'Axolote', 'Salamandra tigre', 'Rana arborícola','Otro'
                ],
                Especie::Exotico => [
                    'Hurón', 'Erizo pigmeo africano', 'Zarigüeya', 'Sugar Glider', 'Mapache (en algunos países)',
                    'Mono Tití','Otro'
                ],
                Especie::Insecto => [
                    'Tarántula', 'Escorpión emperador', 'Mantis religiosa', 'Cucaracha gigante de Madagascar',
                    'Mariposa', 'Grillo','Otro'
                ]

        };
    }
 // 👇 Método para obtener todas las razas agrupadas por especie
    public static function todasLasRazasPorEspecie(): array
    {
        $result = [];
        foreach (self::cases() as $especie) {
            $result[$especie->value] = $especie->razas();
        }
        return $result;
    }

    // 👇 Opcional: obtener lista de especies con label (útil para el select)
    public static function labels(): array
    {
        return array_map(fn($e) => [
            'value' => $e->value,
            'label' => ucfirst($e->name) // o puedes usar un array personalizado
        ], self::cases());
    }
}
