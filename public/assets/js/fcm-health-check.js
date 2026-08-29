/**
 * FCM Health Check & Auto Recovery
 * Pastikan FCM token selalu valid dan up-to-date
 */

(function () {
    "use strict";

    const HEALTH_CHECK_INTERVAL = 30 * 60 * 1000; // 30 menit
    const TOKEN_REFRESH_DAYS = 7; // Refresh token setiap 7 hari

    /**
     * Check FCM health status
     */
    async function checkFcmHealth() {
        try {
            // Skip jika tidak login
            if (!window.userId) {
                console.log("[FCM Health] User not logged in, skipping check");
                return;
            }

            // Skip jika permission tidak granted
            if (Notification.permission !== "granted") {
                console.log(
                    "[FCM Health] Notification not granted, skipping check"
                );
                return;
            }

            // Skip jika Service Worker tidak ready
            if (!serviceWorkerReady) {
                console.log(
                    "[FCM Health] Service Worker not ready, skipping check"
                );
                return;
            }

            const savedToken = localStorage.getItem("fcm_token");
            const lastRefresh = localStorage.getItem("fcm_token_refresh_time");

            if (!savedToken) {
                console.log("[FCM Health] No saved token found");
                return;
            }

            // Check apakah token perlu di-refresh (setiap 7 hari)
            const daysSinceRefresh = lastRefresh
                ? (Date.now() - parseInt(lastRefresh)) / (1000 * 60 * 60 * 24)
                : TOKEN_REFRESH_DAYS + 1;

            if (daysSinceRefresh > TOKEN_REFRESH_DAYS) {
                console.log("[FCM Health] Token expired, refreshing...");
                await refreshFcmToken();
            } else {
                console.log(
                    "[FCM Health] Token is valid (" +
                        Math.floor(daysSinceRefresh) +
                        " days old)"
                );
            }
        } catch (error) {
            console.error("[FCM Health] Health check error:", error);
        }
    }

    /**
     * Refresh FCM token
     */
    async function refreshFcmToken() {
        try {
            if (!window.messaging || !swRegistration) {
                console.error("[FCM Health] Messaging or SW not available");
                return;
            }

            console.log("[FCM Health] Getting new token...");

            const newToken = await messaging.getToken({
                vapidKey: VAPID_KEY,
                serviceWorkerRegistration: swRegistration,
            });

            if (newToken) {
                console.log("[FCM Health] New token obtained");

                // Save new token
                if (typeof saveFcmToken === "function") {
                    await saveFcmToken(newToken);
                    localStorage.setItem(
                        "fcm_token_refresh_time",
                        Date.now().toString()
                    );
                    console.log("[FCM Health] ✅ Token refreshed successfully");
                }
            }
        } catch (error) {
            console.error("[FCM Health] Token refresh failed:", error);
        }
    }

    /**
     * Clean up invalid tokens from localStorage
     */
    function cleanupLocalStorage() {
        // Hapus token lama yang mungkin corrupt
        const savedToken = localStorage.getItem("fcm_token");
        if (savedToken && savedToken.length < 50) {
            console.log("[FCM Health] Invalid token detected, cleaning up...");
            localStorage.removeItem("fcm_token");
            localStorage.removeItem("fcm_token_refresh_time");
            localStorage.removeItem("notification_enabled");
        }
    }

    /**
     * Initialize health check
     */
    function initHealthCheck() {
        console.log("[FCM Health] Health check system initialized");

        // Cleanup on start
        cleanupLocalStorage();

        // Run health check every 30 minutes
        setInterval(checkFcmHealth, HEALTH_CHECK_INTERVAL);

        // Run initial check after 1 minute
        setTimeout(checkFcmHealth, 60000);
    }

    // Start health check when page loads
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initHealthCheck);
    } else {
        initHealthCheck();
    }

    // Export for manual testing
    window.fcmHealthCheck = checkFcmHealth;
    window.refreshFcmToken = refreshFcmToken;
})();
