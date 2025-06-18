<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Optimization Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration settings for application optimization
    |
    */

    'cache' => [
        'enabled' => env('CACHE_ENABLED', true),
        'ttl' => env('CACHE_TTL', 3600),
        'compress' => env('CACHE_COMPRESS', false),
        'prefix' => env('CACHE_PREFIX', 'petwallet_'),
    ],

    'database' => [
        'query_cache' => env('DB_QUERY_CACHE', true),
        'eager_loading' => env('DB_EAGER_LOADING', true),
        'connection_limit' => env('DB_CONNECTION_LIMIT', 10),
        'slow_query_log' => env('DB_SLOW_QUERY_LOG', false),
        'slow_query_threshold' => env('DB_SLOW_QUERY_THRESHOLD', 1000), // ms
    ],

    'images' => [
        'compress' => env('IMAGE_COMPRESS', true),
        'quality' => env('IMAGE_QUALITY', 85),
        'max_width' => env('IMAGE_MAX_WIDTH', 1920),
        'max_height' => env('IMAGE_MAX_HEIGHT', 1080),
        'webp_enabled' => env('IMAGE_WEBP_ENABLED', true),
    ],

    'assets' => [
        'minify' => env('ASSETS_MINIFY', true),
        'combine' => env('ASSETS_COMBINE', true),
        'version' => env('ASSETS_VERSION', true),
        'cdn_enabled' => env('ASSETS_CDN_ENABLED', false),
        'cdn_url' => env('ASSETS_CDN_URL', ''),
    ],

    'performance' => [
        'gzip_enabled' => env('GZIP_ENABLED', true),
        'browser_cache' => env('BROWSER_CACHE', true),
        'http2_enabled' => env('HTTP2_ENABLED', true),
        'preload_critical' => env('PRELOAD_CRITICAL', true),
    ],

    'monitoring' => [
        'enabled' => env('PERFORMANCE_MONITORING', false),
        'log_slow_requests' => env('LOG_SLOW_REQUESTS', true),
        'slow_request_threshold' => env('SLOW_REQUEST_THRESHOLD', 2000), // ms
        'memory_limit_warning' => env('MEMORY_LIMIT_WARNING', 128), // MB
    ],

    'maintenance' => [
        'auto_clean_logs' => env('AUTO_CLEAN_LOGS', true),
        'log_retention_days' => env('LOG_RETENTION_DAYS', 30),
        'auto_optimize_db' => env('AUTO_OPTIMIZE_DB', false),
        'optimize_frequency' => env('OPTIMIZE_FREQUENCY', 'weekly'), // daily, weekly, monthly
    ],
]; 