import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        VitePWA({
            registerType: 'autoUpdate',
            injectRegister: 'auto',
            includeAssets: [
                'favicon.ico',
                'favicon.png',
                'apple-touch-icon.png',
                'pwa-icons/*.png',
            ],
            manifest: {
                name: 'Sandy Juice',
                short_name: 'Sandy Juice',
                description: 'Jus naturels pressés à froid — commandez en ligne',
                theme_color: '#E85A14',
                background_color: '#E85A14',
                display: 'standalone',
                orientation: 'portrait',
                scope: '/',
                start_url: '/',
                lang: 'fr',
                icons: [
                    // any — fond transparent, logo visible tel quel
                    { src: '/pwa-icons/icon-72x72.png',   sizes: '72x72',   type: 'image/png', purpose: 'any' },
                    { src: '/pwa-icons/icon-96x96.png',   sizes: '96x96',   type: 'image/png', purpose: 'any' },
                    { src: '/pwa-icons/icon-128x128.png', sizes: '128x128', type: 'image/png', purpose: 'any' },
                    { src: '/pwa-icons/icon-144x144.png', sizes: '144x144', type: 'image/png', purpose: 'any' },
                    { src: '/pwa-icons/icon-152x152.png', sizes: '152x152', type: 'image/png', purpose: 'any' },
                    { src: '/pwa-icons/icon-192x192.png', sizes: '192x192', type: 'image/png', purpose: 'any' },
                    { src: '/pwa-icons/icon-384x384.png', sizes: '384x384', type: 'image/png', purpose: 'any' },
                    { src: '/pwa-icons/icon-512x512.png', sizes: '512x512', type: 'image/png', purpose: 'any' },
                    // maskable — logo sur fond rouge #E85A14 (adaptatif Android)
                    { src: '/pwa-icons/icon-192x192-maskable.png', sizes: '192x192', type: 'image/png', purpose: 'maskable' },
                    { src: '/pwa-icons/icon-512x512-maskable.png', sizes: '512x512', type: 'image/png', purpose: 'maskable' },
                ],
            },
            workbox: {
                globPatterns: ['**/*.{js,css,html,ico,png,svg,webp}'],
                runtimeCaching: [
                    {
                        urlPattern: /^https:\/\/sandyjuice\.cm\/products/,
                        handler: 'NetworkFirst',
                        options: {
                            cacheName: 'products-cache',
                            expiration: { maxEntries: 50, maxAgeSeconds: 300 },
                        },
                    },
                    {
                        urlPattern: /^https:\/\/sandyjuice\.cm\//,
                        handler: 'NetworkFirst',
                        options: {
                            cacheName: 'pages-cache-v2',
                            expiration: { maxEntries: 20, maxAgeSeconds: 300 },
                        },
                    },
                ],
            },
        }),
    ],
    css: {
        postcss: './postcss.config.cjs',
    },
});
