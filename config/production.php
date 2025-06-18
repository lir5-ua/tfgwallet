<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Production Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration settings optimized for production environment
    |
    */

    'debug' => false,
    'cache' => [
        'driver' => 'redis',
        'ttl' => 3600,
    ],
    
    'session' => [
        'driver' => 'redis',
        'lifetime' => 120,
        'expire_on_close' => false,
    ],
    
    'queue' => [
        'default' => 'redis',
        'connections' => [
            'redis' => [
                'driver' => 'redis',
                'connection' => 'default',
                'queue' => env('REDIS_QUEUE', 'default'),
                'retry_after' => 90,
                'block_for' => null,
            ],
        ],
    ],
    
    'logging' => [
        'default' => 'stack',
        'channels' => [
            'stack' => [
                'driver' => 'stack',
                'channels' => ['daily', 'slack'],
                'ignore_exceptions' => false,
            ],
            'daily' => [
                'driver' => 'daily',
                'path' => storage_path('logs/laravel.log'),
                'level' => env('LOG_LEVEL', 'error'),
                'days' => 14,
            ],
        ],
    ],
    
    'optimization' => [
        'enable_query_cache' => true,
        'enable_view_cache' => true,
        'enable_route_cache' => true,
        'enable_config_cache' => true,
        'enable_autoloader_optimization' => true,
    ],
]; 