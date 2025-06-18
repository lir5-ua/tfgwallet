<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $ttl = 300): Response
    {
        // Solo cachear en producción y para usuarios no autenticados
        if (app()->environment('production') && !auth()->check()) {
            $cacheKey = 'page_cache_' . md5($request->fullUrl());
            
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }
            
            $response = $next($request);
            
            // Solo cachear respuestas exitosas
            if ($response->getStatusCode() === 200) {
                Cache::put($cacheKey, $response, $ttl);
            }
            
            return $response;
        }
        
        return $next($request);
    }
} 