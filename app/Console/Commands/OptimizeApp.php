<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OptimizeApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:optimize {--clear-cache : Clear all caches} {--optimize : Run all optimizations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize the application for better performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting application optimization...');

        if ($this->option('clear-cache')) {
            $this->clearAllCaches();
        }

        if ($this->option('optimize')) {
            $this->runAllOptimizations();
        }

        $this->info('✅ Optimization completed successfully!');
    }

    private function clearAllCaches()
    {
        $this->info('🧹 Clearing all caches...');
        
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        
        $this->info('✅ All caches cleared successfully!');
    }

    private function runAllOptimizations()
    {
        $this->info('⚡ Running all optimizations...');

        // Optimizar configuración
        $this->info('📝 Optimizing configuration...');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');

        // Optimizar autoloader
        $this->info('🔄 Optimizing autoloader...');
        exec('composer dump-autoload --optimize');

        // Limpiar logs antiguos
        $this->info('🗑️ Cleaning old logs...');
        $this->cleanOldLogs();

        // Optimizar base de datos
        $this->info('🗄️ Optimizing database...');
        $this->optimizeDatabase();

        // Optimizar imágenes
        $this->info('🖼️ Optimizing images...');
        $this->optimizeImages();

        // Limpiar cache temporal
        $this->info('🧹 Cleaning temporary files...');
        $this->cleanTempFiles();

        $this->info('✅ All optimizations completed!');
    }

    private function cleanOldLogs()
    {
        $logPath = storage_path('logs');
        $files = glob($logPath . '/*.log');
        
        foreach ($files as $file) {
            if (filemtime($file) < strtotime('-30 days')) {
                unlink($file);
                $this->line("Deleted old log: " . basename($file));
            }
        }
    }

    private function optimizeDatabase()
    {
        try {
            // Optimizar tablas SQLite
            if (config('database.default') === 'sqlite') {
                DB::statement('VACUUM');
                DB::statement('ANALYZE');
                $this->info('SQLite database optimized');
            }
        } catch (\Exception $e) {
            $this->warn('Database optimization skipped: ' . $e->getMessage());
        }
    }

    private function cleanTempFiles()
    {
        $tempPaths = [
            storage_path('framework/cache/data'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
        ];

        foreach ($tempPaths as $path) {
            if (is_dir($path)) {
                $files = glob($path . '/*');
                foreach ($files as $file) {
                    if (is_file($file) && filemtime($file) < strtotime('-7 days')) {
                        unlink($file);
                    }
                }
            }
        }
    }

    private function optimizeImages()
    {
        $imagePath = storage_path('app/public/mascotas');
        if (!is_dir($imagePath)) {
            return;
        }

        $files = glob($imagePath . '/*.{jpg,jpeg,png}', GLOB_BRACE);
        foreach ($files as $file) {
            // Comprimir imágenes si es posible
            $this->compressImage($file);
        }
    }

    private function compressImage($filePath)
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        if ($extension === 'jpg' || $extension === 'jpeg') {
            $image = imagecreatefromjpeg($filePath);
            if ($image) {
                imagejpeg($image, $filePath, 85); // Calidad 85%
                imagedestroy($image);
            }
        } elseif ($extension === 'png') {
            $image = imagecreatefrompng($filePath);
            if ($image) {
                imagepng($image, $filePath, 6); // Compresión 6
                imagedestroy($image);
            }
        }
    }
} 