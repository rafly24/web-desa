# UPDATE SISTEM PENGAJUAN SURAT - AUTH WARGA

## Perubahan Yang Dilakukan

Sistem pengajuan surat telah diubah dari **sistem publik tanpa login** menjadi **sistem dengan autentikasi warga**. Warga sekarang harus login terlebih dahulu untuk mengajukan surat dan bisa tracking progress pengajuannya.

## 📁 File Baru Yang Dibuat

### 1. Migrations

-   `database/migrations/2024_12_16_000001_add_user_id_to_pengajuan_surat_table.php`

    -   Menambah kolom `user_id` ke tabel `pengajuan_surat`
    -   Mengubah kolom data pemohon jadi nullable (diambil dari user profile)

-   `database/migrations/2024_12_16_000002_add_profile_fields_to_users_table.php`
    -   Menambah field profil warga: `role`, `nik`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `alamat`, `rt_rw`, `no_telepon`

### 2. Controller

-   `app/Http/Controllers/WargaDashboardController.php`
    -   `index()` - Dashboard warga dengan statistik pengajuan
    -   `show($id)` - Detail pengajuan surat milik warga
    -   `profile()` - Halaman edit profil
    -   `updateProfile()` - Update data profil warga

### 3. Views

-   `resources/views/warga/dashboard.blade.php` - Dashboard warga
-   `resources/views/warga/pengajuan-detail.blade.php` - Detail pengajuan dengan timeline
-   `resources/views/warga/profile.blade.php` - Form edit profil warga

### 4. Update Files

-   `app/Models/User.php`

    -   Tambah field fillable: `role`, `nik`, `tempat_lahir`, dll
    -   Tambah relasi: `pengajuanSurat()`, `isAdmin()`, `isWarga()`

-   `app/Models/PengajuanSurat.php`

    -   Tambah `user_id` ke fillable
    -   Tambah relasi: `user()`

-   `app/Http/Controllers/PengajuanSuratController.php`

    -   Tambah middleware `auth` kecuali untuk index
    -   Data pemohon diambil otomatis dari user profile
    -   Redirect ke dashboard setelah submit

-   `resources/views/pengajuan-surat/create.blade.php`

    -   Form disederhanakan: hanya input keperluan
    -   Data pemohon ditampilkan read-only dari profil
    -   Upload dokumen jadi opsional

-   `routes/web.php`
    -   Tambah route group `middleware(['auth'])` untuk warga dashboard

## 🚀 Cara Install

### 1. Jalankan Migration Baru

```bash
php artisan migrate
```

### 2. Update User Admin yang Sudah Ada

```sql
UPDATE users SET role = 'admin' WHERE email = 'admin@gmail.com';
```

### 3. Buat Akun Warga Dummy (Optional)

```sql
INSERT INTO users (name, email, password, role, nik, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, rt_rw, no_telepon, created_at, updated_at)
VALUES (
  'Ahmad Warga',
  'warga@test.com',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
  'warga',
  '3301012345678901',
  'Bandung',
  '1990-01-15',
  'Laki-laki',
  'Jl. Merdeka No. 123',
  '001/002',
  '081234567890',
  NOW(),
  NOW()
);
```

Password default: `password`

## 📝 Alur Penggunaan

### A. WARGA

1. **Registrasi/Login**

    - Warga harus registrasi dulu atau login
    - Setelah login, lengkapi profil di menu "Profile"

2. **Lengkapi Profil**

    - Masuk ke `/warga/profile`
    - Isi semua data: NIK, Alamat, Tanggal Lahir, dll
    - Data ini akan otomatis dipakai untuk pengajuan surat

3. **Ajukan Surat**

    - Klik "Pengajuan Surat" atau masuk ke dashboard
    - Pilih jenis surat
    - Isi keperluan surat
    - Upload dokumen pendukung (opsional)
    - Submit

4. **Tracking di Dashboard**
    - Masuk ke `/warga/dashboard`
    - Lihat semua pengajuan beserta statusnya
    - Klik "Detail" untuk melihat progress
    - Download surat jika sudah selesai

### B. ADMIN

Tidak ada perubahan pada admin panel. Admin tetap bisa:

-   Lihat semua pengajuan di `/admin/pengajuan-surat`
-   Update status pengajuan
-   Upload surat jadi

## 🔑 Routes Baru

```php
// Warga Dashboard (Harus Login)
GET  /warga/dashboard           - Dashboard warga
GET  /warga/pengajuan/{id}      - Detail pengajuan
GET  /warga/profile             - Edit profil
PUT  /warga/profile             - Update profil
```

## 💡 Fitur Unggulan

✅ **Auto-fill Data** - Data pemohon otomatis dari profil, tidak perlu input ulang
✅ **Dashboard Personal** - Warga bisa lihat semua pengajuannya
✅ **Real-time Tracking** - Lihat status: Pending → Diproses → Selesai
✅ **Timeline Visual** - Timeline status dengan icon check
✅ **Download Surat** - Langsung download surat jadi dari dashboard
✅ **Notifikasi** - SweetAlert saat berhasil kirim pengajuan
✅ **Profile Management** - Warga bisa update data sendiri

## ⚠️ Catatan Penting

1. **Warga yang sudah pernah mengajukan** sebelum update ini:

    - Pengajuan lama (tanpa user_id) tetap bisa dilihat di admin
    - Tapi tidak muncul di dashboard warga (karena belum ada user_id)

2. **Menu Header**:

    - Perlu update header/navigation untuk tambahkan link ke Dashboard Warga
    - Tampilkan nama user yang sedang login
    - Tambah tombol Logout

3. **Registrasi Default**:
    - Laravel sudah punya route `/register` dan `/login`
    - Semua user baru default role = 'warga'
    - Bisa custom form registrasi sesuai kebutuhan

## 🎨 Rekomendasi Tambahan (Opsional)

1. **Email Notification**

    - Kirim email saat status berubah
    - Kirim email saat surat jadi

2. **WhatsApp Notification**

    - Integrasi API WhatsApp
    - Notif via WA saat status update

3. **PWA Push Notification**

    - Push notif browser saat ada update

4. **Profile Photo Upload**

    - Upload foto profil warga
    - Tampilkan di dashboard

5. **Export History**
    - Export riwayat pengajuan ke PDF/Excel

---

**Updated:** 16 Desember 2024
**Oleh:** GitHub Copilot Assistant
