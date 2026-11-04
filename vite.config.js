import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/site.css',
                'resources/js/site.js',
                'resources/css/admin.css',
                'resources/js/admin.js',
                'resources/css/error.css',
            ],
            refresh: true,
        }),
    ],
    build: {
        cssMinify: true,
        // Small assets are inlined only when they are tiny; fonts stay as separate cached files.
        assetsInlineLimit: 2048,
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
