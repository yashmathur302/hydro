import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/site.css',
                'resources/js/site.js',
                'resources/scss/admin.scss',
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
    css: {
        preprocessorOptions: {
            scss: {
                // The vendored Hope UI / Bootstrap 5.2 source uses Sass features that newer
                // Sass versions flag as deprecated; they still compile correctly.
                quietDeps: true,
                silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'mixed-decls', 'slash-div'],
            },
        },
    },
    build: {
        cssMinify: true,
        // Never inline assets as data: URIs: the Content Security Policy only allows
        // fonts and images from our own domain, and separate files cache better.
        assetsInlineLimit: 0,
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
