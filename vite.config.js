import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'frontend/sass/app.scss',
                'frontend/js/app.js',
            ],
            refresh: true,
        }),
        react(),
    ],
});
