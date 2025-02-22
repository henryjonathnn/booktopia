import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        minify: 'esbuild', // Gunakan esbuild untuk minifikasi yang lebih cepat
        manifest: true,
        outDir: 'public/build',
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['alpinejs'], // Pisahkan library besar agar caching lebih efisien
                },
            },
        },
    },
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
