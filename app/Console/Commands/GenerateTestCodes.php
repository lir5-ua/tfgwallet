<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Mascota;
use App\Models\AccesoMascota;
use Illuminate\Support\Str;

class GenerateTestCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:generate-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generar códigos de prueba para mascotas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generando códigos de prueba para mascotas...');

        $mascotas = Mascota::with('usuario')->get();

        if ($mascotas->isEmpty()) {
            $this->error('No hay mascotas en la base de datos.');
            return Command::FAILURE;
        }

        foreach ($mascotas as $mascota) {
            // Limpiar códigos anteriores no usados
            AccesoMascota::where('mascota_id', $mascota->id)->where('usado', false)->delete();

            // Generar código único
            do {
                $codigo = strtoupper(Str::random(8));
            } while (AccesoMascota::where('codigo', $codigo)->exists());

            $acceso = AccesoMascota::create([
                'mascota_id' => $mascota->id,
                'codigo' => $codigo,
                'expires_at' => now()->addDays(7), // 7 días de validez
                'usado' => false,
            ]);

            $this->info("Mascota: {$mascota->nombre} (Usuario: {$mascota->usuario->name}) - Código: {$codigo}");
        }

        $this->info('¡Códigos generados exitosamente!');
        $this->info('Para usar un código:');
        $this->info('1. Ve a /acceso/historial como veterinario');
        $this->info('2. Introduce el código');
        $this->info('3. Una vez usado, podrás crear citas con ese código');
        
        return Command::SUCCESS;
    }
}
