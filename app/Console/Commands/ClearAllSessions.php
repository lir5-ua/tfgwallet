<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ClearAllSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:clear-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpiar todas las sesiones de la aplicación';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Limpiando todas las sesiones...');

        // Limpiar sesiones de la base de datos
        DB::table('sessions')->truncate();
        
        $this->info('Sesiones de base de datos limpiadas.');

        // Limpiar archivos de sesión si existen
        $sessionPath = storage_path('framework/sessions');
        if (is_dir($sessionPath)) {
            $files = glob($sessionPath . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            $this->info('Archivos de sesión limpiados.');
        }

        $this->info('¡Todas las sesiones han sido limpiadas exitosamente!');
        
        return Command::SUCCESS;
    }
}
