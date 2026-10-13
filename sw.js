const CACHE_NAME = 'keepai-v46.35.0';
const ASSETS = [
    './',
    './index.php',
    './style-v46.css',
    './app-v46.js',
    './i18n.js',
    './i18n.js?v=46.32.0',
    './privacy.html',
    './terms.html',
    './tutorial.html',
    './manifest.json',
    './192.png',
    './512.png',
    './notas.png',
    './lista.png',
    './checklist.png',
    './evento.png',
    './compromisso.png'
];

self.addEventListener('install', (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(ASSETS))
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.filter((key) => key !== CACHE_NAME)
                    .map((key) => caches.delete(key))
            );
        }).then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    // CRITICAL: Only handle GET requests! Cache API does not support POST/PUT/DELETE
    if (event.request.method !== 'GET') {
        return;
    }

    const url = new URL(event.request.url);

    // CRITICAL: NEVER intercept third-party requests (PayPal, Google, CDNs)
    // Let the browser handle external requests natively!
    if (url.origin !== self.location.origin) {
        return;
    }
    const isCode = event.request.mode === 'navigate' || 
                   url.pathname.endsWith('.js') || 
                   url.pathname.endsWith('.php') || 
                   url.pathname.endsWith('.json');

    if (isCode) {
        event.respondWith(
            fetch(event.request).then((networkResponse) => {
                if (networkResponse && networkResponse.status === 200) {
                    const clone = networkResponse.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone));
                }
                return networkResponse;
            }).catch(() => caches.match(event.request))
        );
        return;
    }

    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request);
        })
    );
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            for (const client of clientList) {
                if (client.url && 'focus' in client) return client.focus();
            }
            if (clients.openWindow) return clients.openWindow('./');
        })
    );
});

self.addEventListener('push', (event) => {
    let data = { title: 'Keep AI: Lembrete', body: 'Você tem um compromisso!' };
    try {
        if (event.data) data = event.data.json();
    } catch (_) {
        if (event.data) data.body = event.data.text();
    }
    event.waitUntil(
        self.registration.showNotification(data.title || 'Keep AI: Lembrete', {
            body: data.body || 'Seu compromisso está na hora!',
            icon: './192.png',
            badge: './192.png',
            vibrate: [200, 100, 200]
        })
    );
});
