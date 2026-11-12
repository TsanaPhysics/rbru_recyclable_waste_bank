/**
 * RBRU Recyclable Waste Bank Management - Service Worker
 * Progressive Web App (PWA) Offline Cache & Background Sync
 */

const CACHE_NAME = 'rbru-waste-bank-v1';
const ASSETS_TO_CACHE = [
  '/rbru_waste_bank/',
  '/rbru_waste_bank/index.php',
  '/rbru_waste_bank/manifest.json',
  '/rbru_waste_bank/assets/icons/icon-192.svg',
  '/rbru_waste_bank/assets/icons/icon-512.svg',
  'https://cdn.tailwindcss.com',
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'
];

// Install Event: Cache Core Static Assets
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log('[ServiceWorker] Pre-caching offline shell');
      return cache.addAll(ASSETS_TO_CACHE).catch((err) => {
        console.warn('[ServiceWorker] Some assets could not be cached on install:', err);
      });
    })
  );
  self.skipWaiting();
});

// Activate Event: Clean Old Caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keyList) => {
      return Promise.all(
        keyList.map((key) => {
          if (key !== CACHE_NAME) {
            console.log('[ServiceWorker] Removing old cache', key);
            return caches.delete(key);
          }
        })
      );
    })
  );
  self.clients.claim();
});

// Fetch Event: Network First with Cache Fallback for dynamic pages
self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') return;

  event.respondWith(
    fetch(event.request)
      .then((networkResponse) => {
        // Cache a clone of static/stylesheet assets
        if (networkResponse && networkResponse.status === 200) {
          const url = event.request.url;
          if (url.includes('.css') || url.includes('.js') || url.includes('.svg') || url.includes('.png')) {
            const responseToCache = networkResponse.clone();
            caches.open(CACHE_NAME).then((cache) => {
              cache.put(event.request, responseToCache);
            });
          }
        }
        return networkResponse;
      })
      .catch(() => {
        // Fallback to cache if offline
        return caches.match(event.request).then((cachedResponse) => {
          if (cachedResponse) {
            return cachedResponse;
          }
          if (event.request.headers.get('accept').includes('text/html')) {
            return caches.match('/rbru_waste_bank/index.php');
          }
        });
      })
  );
});

