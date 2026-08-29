# 🔥 Setup Firebase Cloud Messaging (FCM)

## Langkah 1: Buat Firebase Project

1. **Buka Firebase Console:**

    - Kunjungi: https://console.firebase.google.com/
    - Login dengan akun Google

2. **Buat Project Baru:**

    - Klik "Add project" / "Tambah project"
    - Nama project: `web-desa-cibuni` (atau nama lain)
    - Disable Google Analytics (tidak perlu untuk sekarang)
    - Klik "Create project"

3. **Tunggu project dibuat** (30-60 detik)

---

## Langkah 2: Download Service Account Key (untuk Backend)

1. **Di Firebase Console**, klik ⚙️ (Settings) → **Project settings**

2. **Tab "Service accounts"**

3. Klik **"Generate new private key"**

4. **Download file JSON** (contoh: `web-desa-cibuni-firebase-adminsdk-xxxxx.json`)

5. **Simpan file ke:** `storage/app/firebase/` (buat folder `firebase` jika belum ada)

    ```
    web-desa/
    └── storage/
        └── app/
            └── firebase/
                └── web-desa-firebase-credentials.json  ← SIMPAN DI SINI
    ```

6. **Rename file** jadi: `web-desa-firebase-credentials.json`

---

## Langkah 3: Get Firebase Web Config (untuk Frontend)

1. Di **Project Overview**, klik ikon **</>** (Web)

2. **Daftarkan app:**

    - App nickname: `Web Desa PWA`
    - ✅ Centang "Also set up Firebase Hosting" (optional)
    - Klik "Register app"

3. **Copy konfigurasi Firebase** yang muncul:

    ```javascript
    const firebaseConfig = {
        apiKey: "AIzaSyXXXXXXXXXXXXXXXXXXXXXXXX",
        authDomain: "web-desa-xxxxx.firebaseapp.com",
        projectId: "web-desa-xxxxx",
        storageBucket: "web-desa-xxxxx.appspot.com",
        messagingSenderId: "1234567890",
        appId: "1:1234567890:web:xxxxxxxxxxxxx",
    };
    ```

4. **SIMPAN** informasi ini, nanti akan dipakai di frontend!

---

## Langkah 4: Enable Cloud Messaging API

1. Masih di Firebase Console, buka tab **"Cloud Messaging"**

2. Jika ada notifikasi "Cloud Messaging API disabled", klik **"Enable"**

3. **Copy "Server key"** (Legacy server key)
    - SIMPAN key ini untuk nanti

---

## Langkah 5: Update .env Laravel

Tambahkan ke file `.env`:

```env
# Firebase Configuration
FIREBASE_CREDENTIALS=storage/app/firebase/web-desa-firebase-credentials.json
FIREBASE_DATABASE_URL=https://web-desa-xxxxx-default-rtdb.firebaseio.com
```

**Note:**

-   Ganti `web-desa-xxxxx` dengan project ID Anda
-   Database URL bisa dikosongkan jika tidak pakai Realtime Database

---

## Langkah 6: Generate VAPID Keys (untuk Web Push)

1. Di Firebase Console, tab **"Cloud Messaging"**

2. Scroll ke bawah ke **"Web configuration"**

3. Klik **"Generate key pair"** di bagian "Web Push certificates"

4. **Copy VAPID key** yang muncul (contoh: `BK3RNwTe3H0-T...`)

5. **SIMPAN** key ini untuk nanti dipakai di frontend

---

## Setelah Semua Selesai:

✅ File `web-desa-firebase-credentials.json` sudah ada di `storage/app/firebase/`
✅ `.env` sudah diupdate dengan `FIREBASE_CREDENTIALS`
✅ Sudah punya Firebase Web Config
✅ Sudah punya VAPID key

**Lanjut ke implementasi code!** 🚀

---

## Troubleshooting

**Error: "Permission denied"**

-   Pastikan file credentials.json ada di folder yang benar
-   Cek permission file (readable)

**Error: "Project not found"**

-   Pastikan project ID benar di .env
-   Pastikan credentials file dari project yang sama

**Push notification tidak terkirim:**

-   Pastikan Cloud Messaging API sudah enabled
-   Cek FCM token sudah tersimpan di database
-   Cek console browser untuk error
