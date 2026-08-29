// Firebase Configuration
const firebaseConfig = {
    apiKey: "AIzaSyCZNgUGvCNZPXneSjmPobfyZAeHJNKo0ho",
    authDomain: "desa-3611a.firebaseapp.com",
    projectId: "desa-3611a",
    storageBucket: "desa-3611a.firebasestorage.app",
    messagingSenderId: "969771099791",
    appId: "1:969771099791:web:cf4af28bf02f1a147432fa",
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

// VAPID Key dari Firebase Console
const VAPID_KEY =
    "BMBKknyFhtLYQ3_-otP5Qz0CssaGLKedeLwKCOjSounZUfRcdZKLky44b5t9xxC6N8S0P4XCWT2bTc6nVLZXOMo";

// Flag untuk track SW readiness
let serviceWorkerReady = false;
let swRegistration = null;

/**
 * Request notification permission dan get FCM token
 */
async function requestNotificationPermission() {
    try {
        // Check if notification supported
        if (!("Notification" in window)) {
            alert("Browser Anda tidak mendukung notifikasi");
            console.log("This browser does not support notifications");
            return;
        }

        // Check if already blocked
        if (Notification.permission === "denied") {
            alert(
                "Notifikasi diblokir!\n\nCara mengaktifkan:\n1. Klik icon gembok/info di URL bar\n2. Cari 'Notifications'\n3. Ubah ke 'Allow'\n4. Refresh halaman dan coba lagi"
            );
            console.error("Notification permission is blocked");
            return;
        }

        // CEK Service Worker Ready!
        if (!serviceWorkerReady) {
            alert(
                "⏳ Service Worker masih loading...\n\nTunggu 3-5 detik lalu coba lagi."
            );
            console.error("Service Worker not ready yet!");
            return;
        }

        console.log("Service Worker confirmed ready, proceeding...");

        // Request permission
        const permission = await Notification.requestPermission();

        if (permission === "granted") {
            console.log("Notification permission granted");

            // Get FCM token dengan explicit SW registration
            const token = await messaging.getToken({
                vapidKey: VAPID_KEY,
                serviceWorkerRegistration: swRegistration,
            });

            if (token) {
                console.log(
                    "FCM Token obtained:",
                    token.substring(0, 50) + "..."
                );
                console.log("Will save with user_id:", window.userId);

                const saveResult = await saveFcmToken(token);

                if (saveResult) {
                    alert(
                        "✅ Notifikasi berhasil diaktifkan!\n\nToken tersimpan untuk user_id: " +
                            (window.userId || "guest")
                    );
                } else {
                    alert("⚠️ Token didapat tapi gagal disimpan. Cek console.");
                }
            } else {
                console.log("No registration token available");
                alert("Gagal mendapatkan token notifikasi");
            }
        } else {
            console.log("Notification permission denied");
            alert("Anda menolak izin notifikasi");
        }
    } catch (error) {
        console.error("Error requesting permission:", error);
        alert(
            "Error: " +
                error.message +
                "\n\nCoba refresh halaman dan tunggu beberapa detik sebelum klik tombol notifikasi."
        );
    }
}

/**
 * Save FCM token to backend
 */
async function saveFcmToken(token) {
    try {
        const userId = window.userId || null;

        console.log("=== SAVING FCM TOKEN ===");
        console.log("Token (first 50 chars):", token.substring(0, 50) + "...");
        console.log("User ID:", userId);
        console.log("window.userId:", window.userId);
        console.log("Device:", navigator.userAgent.substring(0, 50) + "...");

        // Coba route debug dulu (lebih sederhana, tanpa CSRF kompleks)
        console.log("Trying debug route first...");

        const debugResponse = await fetch("/debug-save-token", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN":
                    document.querySelector('meta[name="csrf-token"]')
                        ?.content || "",
            },
            body: JSON.stringify({
                token: token,
                device_type: "web",
                browser: navigator.userAgent,
            }),
        });

        if (debugResponse.ok) {
            const debugData = await debugResponse.json();
            console.log("✅ Token saved via debug route:", debugData);

            if (debugData.success) {
                localStorage.setItem("fcm_token", token);
                localStorage.setItem("notification_enabled", "true");
                return true;
            }
        }

        // Fallback ke API route jika debug gagal
        console.log("Debug route failed, trying API route...");

        const response = await fetch("/api/fcm-token", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN":
                    document.querySelector('meta[name="csrf-token"]')
                        ?.content || "",
            },
            body: JSON.stringify({
                token: token,
                device_type: "web",
                browser: navigator.userAgent,
                user_id: userId,
            }),
        });

        const data = await response.json();
        console.log("Server response:", data);

        if (data.success) {
            console.log("✅ FCM token saved successfully via API");
            localStorage.setItem("fcm_token", token);
            localStorage.setItem("notification_enabled", "true");
            return true;
        } else {
            console.error("❌ Failed to save FCM token:", data.message);
            return false;
        }
    } catch (error) {
        console.error("❌ Error saving FCM token:", error);
        return false;
    }
}

/**
 * Handle foreground notifications
 */
messaging.onMessage((payload) => {
    console.log("Message received:", payload);

    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: "/assets/img/favicon.png",
        badge: "/assets/img/favicon.png",
        data: payload.data,
        tag: payload.data?.type || "default",
        requireInteraction: true,
        actions: [
            {
                action: "open",
                title: "Lihat Detail",
            },
            {
                action: "close",
                title: "Tutup",
            },
        ],
    };

    // Show notification
    if (Notification.permission === "granted") {
        new Notification(notificationTitle, notificationOptions);
    }
});

/**
 * Check if notification already enabled
 */
function isNotificationEnabled() {
    return Notification.permission === "granted";
}

/**
 * Auto request permission on page load (optional)
 */
window.addEventListener("load", async function () {
    console.log("🚀 Initializing Firebase Service Worker...");

    // Register Firebase Service Worker
    if ("serviceWorker" in navigator) {
        try {
            // Register Firebase SW
            const registration = await navigator.serviceWorker.register(
                "/firebase-messaging-sw.js",
                { scope: "/" }
            );

            console.log("✅ Firebase SW registered:", registration);

            // Tunggu sampai SW benar-benar aktif
            await navigator.serviceWorker.ready;

            // Cek apakah ada active SW
            if (registration.active) {
                console.log("✅ Service Worker is ACTIVE and READY!");
                swRegistration = registration;
                serviceWorkerReady = true;
            } else if (registration.installing) {
                console.log("⏳ Service Worker installing...");

                // Tunggu sampai selesai install
                await new Promise((resolve) => {
                    registration.installing.addEventListener(
                        "statechange",
                        (e) => {
                            console.log("SW state changed to:", e.target.state);
                            if (e.target.state === "activated") {
                                console.log("✅ Service Worker ACTIVATED!");
                                swRegistration = registration;
                                serviceWorkerReady = true;
                                resolve();
                            }
                        }
                    );
                });
            } else if (registration.waiting) {
                console.log("⏳ Service Worker waiting...");
                registration.waiting.postMessage({ type: "SKIP_WAITING" });

                // Delay sebentar lalu set ready
                await new Promise((resolve) => setTimeout(resolve, 1000));
                swRegistration = registration;
                serviceWorkerReady = true;
            }

            console.log("🎉 Firebase Service Worker ready for FCM!");
            console.log("serviceWorkerReady flag:", serviceWorkerReady);
        } catch (error) {
            console.error(
                "❌ Firebase Service Worker registration failed:",
                error
            );
            serviceWorkerReady = false;
        }
    } else {
        console.error("❌ Service Worker not supported in this browser");
    }
});

/**
 * Export functions untuk digunakan di HTML
 */
window.requestNotificationPermission = requestNotificationPermission;
window.isNotificationEnabled = isNotificationEnabled;
