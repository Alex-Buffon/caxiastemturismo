const CACHE_NAME = 'caxias-turismo-v2';
const ASSETS_TO_CACHE = [
  '/',
  '/index.php',
  '/manifest.json',
  '/css/style.css',
  '/img/img1.png',
  '/img/img.santalucia.png',
  '/img/img.fazendasouza.png',
  '/img/img.terceiralegua.png',
  '/img/img.galopolis.png',
  '/img/img.turismoreligioso.png',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'
];

// Install service worker e cache inicial
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => cache.addAll(ASSETS_TO_CACHE))
  );
});

// Estratégia de cache: Cache First, Network Fallback
self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request)
      .then((response) => {
        // Cache hit - retorna resposta do cache
        if (response) {
          return response;
        }

        // Cache miss - busca na rede
        return fetch(event.request).then(
          (response) => {
            // Checa se recebemos uma resposta válida
            if(!response || response.status !== 200 || response.type !== 'basic') {
              return response;
            }

            // Clone a resposta
            const responseToCache = response.clone();

            caches.open(CACHE_NAME)
              .then((cache) => {
                cache.put(event.request, responseToCache);
              });

            return response;
          }
        );
      })
  );
});