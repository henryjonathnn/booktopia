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
        manifest: true,
        minify: 'terser',
        cssMinify: true,
        rollupOptions: {
            output: {
                manualChunks: (id) => {
                    if (id.includes('node_modules')) {
                        if (id.includes('alpinejs')) return 'alpine';
                        if (id.includes('@livewire')) return 'livewire';
                        if (id.includes('tailwindcss')) return 'tailwind';
                        return 'vendor';
                    }
                },
                chunkFileNames: 'js/[name].[hash].js',
                entryFileNames: 'js/[name].[hash].js',
                assetFileNames: '[ext]/[name].[hash].[ext]'
            },
        },
        target: 'esnext',
        cssCodeSplit: false,
        assetsInlineLimit: 4096,
    },
    optimizeDeps: {
        include: ['alpinejs', '@livewire/turbolinks'],
        exclude: ['@vite/client', '@inertiajs/inertia'],
    },
    server: {
        hmr: {
            timeout: 1000,
        },
    }
});
