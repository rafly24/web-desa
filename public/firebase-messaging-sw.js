// Firebase Service Worker untuk Cloud Messaging
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

// Skip waiting when message received (untuk update SW lebih cepat)
self.addEventListener("message", (event) => {
    if (event.data && event.data.type === "SKIP_WAITING") {
        console.log("[firebase-messaging-sw.js] Skip waiting...");
        self.skipWaiting();
    }
});

// Activate immediately
self.addEventListener("activate", (event) => {
    console.log("[firebase-messaging-sw.js] Service Worker activated");
    event.waitUntil(clients.claim());
});

// Handle background messages
messaging.onBackgroundMessage((payload) => {
    console.log(
        "[firebase-messaging-sw.js] Received background message:",
        payload
    );

    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: "/assets/img/pwa-icon-192.webp",
        badge: "/assets/img/favicon.png",
        data: payload.data,
        tag: payload.data?.type || "default",
        requireInteraction: true,
        vibrate: [200, 100, 200], // Vibrate pattern
        renotify: true, // Re-notify jika tag sama
        silent: false, // Pastikan ada sound
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
    console.log("[firebase-messaging-sw.js] Notification clicked:", event);

    event.notification.close();

    if (event.action === "open" || !event.action) {
        const urlToOpen = event.notification.data?.url || "/warga/dashboard";

        event.waitUntil(
            clients
                .matchAll({ type: "window", includeUncontrolled: true })
                .then((clientList) => {
                    // Cek apakah ada window yang sudah terbuka
                    for (let client of clientList) {
                        if (
                            client.url.includes(new URL(urlToOpen).pathname) &&
                            "focus" in client
                        ) {
                            return client.focus();
                        }
                    }
                    // Jika tidak ada, buka window baru
                    if (clients.openWindow) {
                        return clients.openWindow(urlToOpen);
                    }
                })
        );
    }
});

// Fallback: Handle push event directly (untuk Android yang tidak support Firebase SDK)
self.addEventListener("push", (event) => {
    console.log("[firebase-messaging-sw.js] Push event received:", event);

    if (event.data) {
        const data = event.data.json();
        const options = {
            body:
                data.notification?.body ||
                data.body ||
                "Anda memiliki notifikasi baru",
            icon: "/assets/img/pwa-icon-192.webp",
            badge: "/assets/img/favicon.png",
            vibrate: [200, 100, 200],
            data: data.data || {},
            requireInteraction: true,
            silent: false,
        };

        event.waitUntil(
            self.registration.showNotification(
                data.notification?.title || data.title || "Portal Desa",
                options
            )
        );
    }
});
