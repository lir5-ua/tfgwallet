<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PerformanceMonitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        $response = $next($request);

        $endTime = microtime(true);
        $endMemory = memory_get_usage();

        $executionTime = ($endTime - $startTime) * 1000; // Convertir a milisegundos
        $memoryUsed = $endMemory - $startMemory;

        // Log de peticiones lentas
        if (config('optimization.monitoring.log_slow_requests', true)) {
            $threshold = config('optimization.monitoring.slow_request_threshold', 2000);
            
            if ($executionTime > $threshold) {
                Log::warning('Slow request detected', [
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'execution_time' => round($executionTime, 2) . 'ms',
                    'memory_used' => round($memoryUsed / 1024 / 1024, 2) . 'MB',
                    'user_agent' => $request->userAgent(),
                    'ip' => $request->ip(),
                ]);
            }
        }

        // Agregar headers de rendimiento
        $response->headers->set('X-Execution-Time', round($executionTime, 2) . 'ms');
        $response->headers->set('X-Memory-Used', round($memoryUsed / 1024 / 1024, 2) . 'MB');

        // Cache de métricas de rendimiento
        if (config('optimization.monitoring.enabled', false)) {
            $this->cachePerformanceMetrics($request, $executionTime, $memoryUsed);
        }

        return $response;
    }

    private function cachePerformanceMetrics(Request $request, float $executionTime, int $memoryUsed)
    {
        $key = 'performance_metrics_' . date('Y-m-d');
        $metrics = Cache::get($key, []);

        $route = $request->route() ? $request->route()->getName() : $request->path();
        
        if (!isset($metrics[$route])) {
            $metrics[$route] = [
                'count' => 0,
                'total_time' => 0,
                'total_memory' => 0,
                'avg_time' => 0,
                'avg_memory' => 0,
                'max_time' => 0,
                'min_time' => PHP_FLOAT_MAX,
            ];
        }

        $metrics[$route]['count']++;
        $metrics[$route]['total_time'] += $executionTime;
        $metrics[$route]['total_memory'] += $memoryUsed;
        $metrics[$route]['avg_time'] = $metrics[$route]['total_time'] / $metrics[$route]['count'];
        $metrics[$route]['avg_memory'] = $metrics[$route]['total_memory'] / $metrics[$route]['count'];
        $metrics[$route]['max_time'] = max($metrics[$route]['max_time'], $executionTime);
        $metrics[$route]['min_time'] = min($metrics[$route]['min_time'], $executionTime);

        Cache::put($key, $metrics, 86400); // Cache por 24 horas
    }
} 