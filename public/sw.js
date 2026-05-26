const CACHE_NAME = 'rdbarber-v2';

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(['/']);
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k)))
        )
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    event.respondWith(
        fetch(event.request).catch(() => caches.match(event.request))
    );
});

// ===== NOTIFICACIONES PUSH =====
self.addEventListener('push', (event) => {
    const data = event.data ? event.data.json() : {};

    const title = data.title || 'RD Barbería';
    const options = {
        body: data.body || 'Tienes una nueva notificación.',
        icon: data.icon || '/icons/icon-192.png',
        badge: data.badge || '/icons/icon-72.png',
        tag: data.tag || 'rdbarber-notif',
        renotify: true,
        vibrate: [200, 100, 200, 100, 400],  // Patrón de vibración: vibra-pausa-vibra-pausa-vibra
        data: {
            url: data.url || '/',
        },
        actions: data.actions || [],
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

// Al hacer clic en la notificación, abrir/enfocar la app
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const targetUrl = event.notification.data?.url || '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            for (const client of clientList) {
                if (client.url.includes(targetUrl) && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
