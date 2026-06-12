import { precacheAndRoute } from 'workbox-precaching';
import { registerRoute } from 'workbox-routing';
import { NetworkFirst } from 'workbox-strategies';
import { ExpirationPlugin } from 'workbox-expiration';

// Precache all assets injected by vite-plugin-pwa
precacheAndRoute(self.__WB_MANIFEST);

// Network-first for HTML pages and API calls
registerRoute(
    ({ request }) => request.mode === 'navigate',
    new NetworkFirst({
        cacheName: 'pages-cache-v3',
        plugins: [new ExpirationPlugin({ maxEntries: 20, maxAgeSeconds: 300 })],
    })
);

registerRoute(
    ({ url }) => url.pathname.startsWith('/products'),
    new NetworkFirst({
        cacheName: 'products-cache',
        plugins: [new ExpirationPlugin({ maxEntries: 50, maxAgeSeconds: 300 })],
    })
);

// Push notification handler — fires even when the app is closed
self.addEventListener('push', (event) => {
    if (!event.data) return;

    let data = {};
    try { data = event.data.json(); } catch { data = { title: 'Sandy Juice', body: event.data.text() }; }

    const title = data.title ?? 'Sandy Juice';
    const options = {
        body:  data.body  ?? '',
        icon:  '/pwa-icons/icon-192x192.png',
        badge: '/pwa-icons/icon-72x72.png',
        image: data.image ?? undefined,
        data:  { url: data.url ?? '/' },
        vibrate: [200, 100, 200],
        requireInteraction: false,
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

// Open / focus the correct page when the user taps the notification
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const targetUrl = event.notification.data?.url ?? '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            for (const client of clientList) {
                if (client.url === targetUrl && 'focus' in client) {
                    return client.focus();
                }
            }
            return clients.openWindow(targetUrl);
        })
    );
});
