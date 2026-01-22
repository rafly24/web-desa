const CACHE_NAME = "pwa-static-v1";
const PRECACHE_URLS = [
    "/",
    "/assets/css/style.css",
    "/assets/vendor/bootstrap/css/bootstrap.min.css",
    "/assets/vendor/bootstrap/js/bootstrap.bundle.min.js",
    "/assets/vendor/aos/aos.css",
    "/assets/vendor/aos/aos.js",
    "/assets/vendor/boxicons/css/boxicons.min.css",
    "/assets/vendor/glightbox/css/glightbox.min.css",
    "/assets/vendor/glightbox/js/glightbox.min.js",
    "/assets/vendor/swiper/swiper-bundle.min.css",
    "/assets/vendor/swiper/swiper-bundle.min.js",
    "/assets/js/main.js",
    "/assets/img/favicon.png",
    "/assets/img/apple-touch-icon.png",
];

self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(PRECACHE_URLS))
    );
});

self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((cacheNames) =>
                Promise.all(
                    cacheNames
                        .filter((name) => name !== CACHE_NAME)
                        .map((name) => caches.delete(name))
                )
            )
    );
});

self.addEventListener("fetch", (event) => {
    const request = event.request;
    // Bypass non-GET or cross-origin requests untuk keamanan dan menghindari efek samping
    if (
        request.method !== "GET" ||
        new URL(request.url).origin !== location.origin
    ) {
        return;
    }

    // Stale-while-revalidate untuk aset statis, tidak mengubah API behavior
    event.respondWith(
        caches.match(request).then((cached) => {
            const networkFetch = fetch(request)
                .then((response) => {
                    const responseClone = response.clone();
                    caches
                        .open(CACHE_NAME)
                        .then((cache) => cache.put(request, responseClone));
                    return response;
                })
                .catch(() => cached);
            return cached || networkFetch;
        })
    );
});
