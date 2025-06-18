<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PerformanceMetrics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:performance {--date= : Date to show metrics for (Y-m-d format)} {--clear : Clear performance metrics}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show application performance metrics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('clear')) {
            $this->clearMetrics();
            return;
        }

        $date = $this->option('date') ?: date('Y-m-d');
        $this->showMetrics($date);
    }

    private function showMetrics($date)
    {
        $key = 'performance_metrics_' . $date;
        $metrics = Cache::get($key, []);

        if (empty($metrics)) {
            $this->warn("No performance metrics found for date: {$date}");
            return;
        }

        $this->info("📊 Performance Metrics for {$date}");
        $this->line('');

        $headers = ['Route', 'Requests', 'Avg Time (ms)', 'Avg Memory (MB)', 'Max Time (ms)', 'Min Time (ms)'];
        $rows = [];

        foreach ($metrics as $route => $data) {
            $rows[] = [
                $route,
                $data['count'],
                round($data['avg_time'], 2),
                round($data['avg_memory'] / 1024 / 1024, 2),
                round($data['max_time'], 2),
                round($data['min_time'], 2),
            ];
        }

        $this->table($headers, $rows);

        // Mostrar estadísticas generales
        $this->showGeneralStats($metrics);
    }

    private function showGeneralStats($metrics)
    {
        $this->line('');
        $this->info('📈 General Statistics');

        $totalRequests = array_sum(array_column($metrics, 'count'));
        $avgTime = array_sum(array_column($metrics, 'avg_time')) / count($metrics);
        $maxTime = max(array_column($metrics, 'max_time'));
        $totalMemory = array_sum(array_column($metrics, 'total_memory'));

        $this->line("Total Requests: {$totalRequests}");
        $this->line("Average Response Time: " . round($avgTime, 2) . "ms");
        $this->line("Maximum Response Time: " . round($maxTime, 2) . "ms");
        $this->line("Total Memory Used: " . round($totalMemory / 1024 / 1024, 2) . "MB");

        // Mostrar rutas más lentas
        $this->showSlowestRoutes($metrics);
    }

    private function showSlowestRoutes($metrics)
    {
        $this->line('');
        $this->info('🐌 Slowest Routes');

        $sorted = collect($metrics)->sortByDesc('avg_time')->take(5);
        
        foreach ($sorted as $route => $data) {
            $this->line("• {$route}: " . round($data['avg_time'], 2) . "ms avg");
        }
    }

    private function clearMetrics()
    {
        $this->info('🧹 Clearing performance metrics...');
        
        // Limpiar métricas de los últimos 30 días
        for ($i = 0; $i < 30; $i++) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $key = 'performance_metrics_' . $date;
            Cache::forget($key);
        }

        $this->info('✅ Performance metrics cleared successfully!');
    }
} 