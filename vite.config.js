import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    // server: {
    //     host: true,        // 0.0.0.0 внутри контейнера
    //     port: 5173,
    //     hmr: {
    //         host: 'localhost', // если заходишь как http://localhost:8181
    //         port: 5173,
    //     },
    // },
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/app.css',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: { base: null, includeAbsolute: false },
            },
        }),
        tailwindcss(),
    ],
})

