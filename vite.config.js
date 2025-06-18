import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        // Optimización de build
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['axios'],
                    ui: ['@fortawesome/fontawesome-free'],
                },
            },
        },
        // Compresión de assets
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
            },
        },
        // Optimización de CSS
        cssCodeSplit: true,
        // Optimización de imágenes
        assetsInlineLimit: 4096,
    },
    // Optimización de desarrollo
    server: {
        hmr: {
            host: 'localhost',
        },
    },
    // Optimización de dependencias
    optimizeDeps: {
        include: ['axios', '@fortawesome/fontawesome-free'],
    },
});
