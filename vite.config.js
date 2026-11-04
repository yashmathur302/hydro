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
    experimental: {
        // Fonts and images referenced from CSS use paths relative to the CSS file,
        // so the site also works when installed in a sub-folder (e.g. /hydro).
        renderBuiltUrl(filename, { hostType }) {
            return hostType === 'css' ? { relative: true } : undefined;
        },
    },
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
