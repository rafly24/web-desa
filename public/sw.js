const CACHE_NAME = "pwa-static-v5";
const PRECACHE_URLS = [
    "/offline",
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

// Import Firebase Messaging untuk Service Worker
importScripts(
    "https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"
);
importScripts(
    "https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js"
);

// Firebase Configuration (sama seperti di firebase-messaging.js)
const firebaseConfig = {
    apiKey: "AIzaSyCZNgUGvCNZPXneSjmPobfyZAeHJNKo0ho",
    authDomain: "desa-3611a.firebaseapp.com",
    projectId: "desa-3611a",
    storageBucket: "desa-3611a.firebasestorage.app",
    messagingSenderId: "969771099791",
    appId: "1:969771099791:web:cf4af28bf02f1a147432fa",
};

// Initialize Firebase in Service Worker
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

// Handle background messages
messaging.onBackgroundMessage((payload) => {
    console.log("Received background message:", payload);

    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: "/assets/img/pwa-icon-192.webp",
        badge: "/assets/img/favicon.png",
        data: payload.data,
        tag: payload.data?.type || "default",
        requireInteraction: true,
        actions: [
            {
                action: "open",
                title: "Lihat",
                icon: "/assets/img/favicon.png",
            },
            {
                action: "close",
                title: "Tutup",
            },
        ],
    };

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions
    );
});

// Handle notification click
self.addEventListener("notificationclick", (event) => {
    console.log("Notification clicked:", event);

    event.notification.close();

    if (event.action === "open" || !event.action) {
        // Open URL dari notification data
        const urlToOpen = event.notification.data?.url || "/";

        event.waitUntil(
            clients
                .matchAll({ type: "window", includeUncontrolled: true })
                .then((clientList) => {
                    // Check if already opened
                    for (let client of clientList) {
                        if (client.url === urlToOpen && "focus" in client) {
                            return client.focus();
                        }
                    }
                    // Open new window
                    if (clients.openWindow) {
                        return clients.openWindow(urlToOpen);
                    }
                })
        );
    }
});

self.addEventListener("install", (event) => {
    self.skipWaiting();
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
            .then(() => self.clients.claim())
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

    // Network-First strategy untuk navigasi HTML (mencegah token CSRF basi/419 error)
    if (request.mode === 'navigate' || (request.method === 'GET' && request.headers.get('accept') && request.headers.get('accept').includes('text/html'))) {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    // Update cache HTML jika sukses fetch
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(request, responseClone));
                    return response;
                })
                .catch(() => {
                    // Jika offline, ambil dari cache atau halaman offline
                    return caches.match(request).then((cached) => {
                        return cached || caches.match('/offline');
                    });
                })
        );
        return;
    }

    // Stale-while-revalidate untuk aset statis (CSS, JS, Image)
    event.respondWith(
        caches.match(request).then((cached) => {
            const networkFetch = fetch(request)
                .then((response) => {
                    // Update cache untuk aset statis
                    if (request.method === "GET" && !request.url.includes('/api/')) {
                        const responseClone = response.clone();
                        caches.open(CACHE_NAME).then((cache) => cache.put(request, responseClone));
                    }
                    return response;
                })
                .catch(() => cached);
            return cached || networkFetch;
        })
    );
});

// Background Sync Logic
const IDB_NAME = 'DesaOfflineDB';
const IDB_VERSION = 1;
const STORE_NAME = 'offline_forms';

function openDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(IDB_NAME, IDB_VERSION);
        request.onerror = () => reject(request.error);
        request.onsuccess = () => resolve(request.result);
    });
}

function getOfflineForms() {
    return openDB().then(db => {
        return new Promise((resolve, reject) => {
            if (!db.objectStoreNames.contains(STORE_NAME)) {
                return resolve([]);
            }
            const tx = db.transaction(STORE_NAME, 'readonly');
            const store = tx.objectStore(STORE_NAME);
            const request = store.getAll();
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }).catch(() => []);
}

function deleteOfflineForm(id) {
    return openDB().then(db => {
        return new Promise((resolve, reject) => {
            const tx = db.transaction(STORE_NAME, 'readwrite');
            const store = tx.objectStore(STORE_NAME);
            const request = store.delete(id);
            request.onsuccess = () => resolve();
            request.onerror = () => reject(request.error);
        });
    });
}

async function syncOfflineForms() {
    const forms = await getOfflineForms();
    for (const form of forms) {
        try {
            const formData = new FormData();
            for (const key in form.formData) {
                if (Array.isArray(form.formData[key])) {
                    form.formData[key].forEach((file) => {
                        // For multiple files like foto_bukti[] we keep the array notation if needed or just append
                        const formKey = key.endsWith('[]') ? key : key + '[]';
                        formData.append(formKey, file, file.name);
                    });
                } else {
                    formData.append(key, form.formData[key]);
                }
            }

            // Tambahkan header khusus offline-sync jika dibutuhkan API
            const response = await fetch(form.url, {
                method: 'POST',
                body: formData,
            });

            if (response.ok) {
                await deleteOfflineForm(form.id);

                // Show success notification to user
                self.registration.showNotification('Data Terkirim!', {
                    body: 'Laporan/Pengajuan Surat yang dikirim saat offline berhasil disinkronisasi ke server.',
                    icon: '/assets/img/favicon.png'
                });
            }
        } catch (err) {
            console.error('Failed to sync offline form, will retry next time.', err);
        }
    }
}

self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-laporan' || event.tag === 'sync-surat') {
        event.waitUntil(syncOfflineForms());
    }
});
