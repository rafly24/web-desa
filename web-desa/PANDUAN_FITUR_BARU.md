# PANDUAN IMPLEMENTASI FITUR PENGAJUAN SURAT & LAPORAN WARGA

## 📋 Fitur yang Telah Dibuat

### 1. **Pengajuan Surat Online**

-   Form pengajuan berbagai jenis surat (SKD, SKU, SKTM, dll)
-   Tracking status pengajuan dengan nomor unik
-   Upload dokumen pendukung (KTP, KK, dll)
-   Sistem status: pending, diproses, selesai, ditolak
-   Download surat jadi (PDF)

### 2. **Laporan Warga / Complaint System**

-   Form pelaporan dengan kategori (infrastruktur, kebersihan, dll)
-   Upload foto bukti (multiple photos)
-   Sistem prioritas: rendah, sedang, tinggi
-   Tracking status laporan
-   Opsi laporan anonim
-   Koordinat GPS (opsional)
-   View counter

---

## 🚀 CARA INSTALASI

### Step 1: Jalankan Migration

```bash
php artisan migrate
```

Ini akan membuat 4 tabel baru:

-   `jenis_surat` - Master jenis surat
-   `pengajuan_surat` - Data pengajuan surat
-   `kategori_laporan` - Master kategori laporan
-   `laporan_warga` - Data laporan warga

### Step 2: Jalankan Seeder untuk Data Awal

```bash
php artisan db:seed --class=JenisSuratSeeder
php artisan db:seed --class=KategoriLaporanSeeder
```

Atau tambahkan ke DatabaseSeeder.php:

```php
public function run()
{
    $this->call([
        JenisSuratSeeder::class,
        KategoriLaporanSeeder::class,
    ]);
}
```

Lalu jalankan:

```bash
php artisan db:seed
```

### Step 3: Buat Symbolic Link untuk Storage

```bash
php artisan storage:link
```

### Step 4: Pastikan Folder Storage Ada

Buat folder-folder berikut di `storage/app/public/`:

-   `pengajuan-surat/ktp/`
-   `pengajuan-surat/kk/`
-   `pengajuan-surat/pendukung/`
-   `surat-jadi/`
-   `laporan-warga/`
-   `laporan-tindak-lanjut/`

---

## 📂 File yang Telah Dibuat

### Models (4 files)

-   `app/Models/JenisSurat.php`
-   `app/Models/PengajuanSurat.php`
-   `app/Models/KategoriLaporan.php`
-   `app/Models/LaporanWarga.php`

### Controllers (4 files)

-   `app/Http/Controllers/PengajuanSuratController.php` (Frontend)
-   `app/Http/Controllers/AdminPengajuanSuratController.php` (Admin)
-   `app/Http/Controllers/LaporanWargaController.php` (Frontend)
-   `app/Http/Controllers/AdminLaporanWargaController.php` (Admin)

### Views Frontend (7 files)

-   `resources/views/pengajuan-surat/index.blade.php`
-   `resources/views/pengajuan-surat/create.blade.php`
-   `resources/views/pengajuan-surat/tracking.blade.php`
-   `resources/views/laporan-warga/index.blade.php`
-   `resources/views/laporan-warga/create.blade.php`
-   `resources/views/laporan-warga/detail.blade.php`
-   `resources/views/laporan-warga/tracking.blade.php`

### Migrations (4 files)

-   `database/migrations/2024_12_15_000001_create_jenis_surat_table.php`
-   `database/migrations/2024_12_15_000002_create_pengajuan_surat_table.php`
-   `database/migrations/2024_12_15_000003_create_kategori_laporan_table.php`
-   `database/migrations/2024_12_15_000004_create_laporan_warga_table.php`

### Seeders (2 files)

-   `database/seeders/JenisSuratSeeder.php`
-   `database/seeders/KategoriLaporanSeeder.php`

---

## 🌐 URL/Routes yang Tersedia

### Frontend (Public)

**Pengajuan Surat:**

-   `/pengajuan-surat` - Daftar jenis surat
-   `/pengajuan-surat/create/{kode_surat}` - Form pengajuan
-   `/pengajuan-surat/tracking/{nomor?}` - Tracking status
-   POST `/pengajuan-surat/cek-status` - Cek status

**Laporan Warga:**

-   `/laporan-warga` - Daftar laporan publik
-   `/laporan-warga/create` - Form buat laporan
-   `/laporan-warga/{id}` - Detail laporan
-   `/laporan-warga/tracking/{nomor?}` - Tracking status
-   POST `/laporan-warga/cek-status` - Cek status

### Admin Panel (Requires Auth)

**Pengajuan Surat:**

-   `/admin/pengajuan-surat` - Daftar pengajuan
-   `/admin/pengajuan-surat/{id}` - Detail pengajuan
-   PUT `/admin/pengajuan-surat/{id}/status` - Update status
-   DELETE `/admin/pengajuan-surat/{id}` - Hapus pengajuan
-   `/admin/jenis-surat` - Kelola jenis surat

**Laporan Warga:**

-   `/admin/laporan-warga` - Daftar laporan
-   `/admin/laporan-warga/{id}` - Detail laporan
-   PUT `/admin/laporan-warga/{id}/status` - Update status & tanggapan
-   DELETE `/admin/laporan-warga/{id}` - Hapus laporan
-   `/admin/kategori-laporan` - Kelola kategori

---

## 📝 TODO: Views Admin yang Perlu Dibuat

Saya telah membuat backend lengkap, namun views admin masih perlu dibuat:

### 1. Admin Pengajuan Surat

-   `resources/views/admin/pengajuan-surat/index.blade.php`
-   `resources/views/admin/pengajuan-surat/show.blade.php`
-   `resources/views/admin/pengajuan-surat/jenis-surat.blade.php`

### 2. Admin Laporan Warga

-   `resources/views/admin/laporan-warga/index.blade.php`
-   `resources/views/admin/laporan-warga/show.blade.php`
-   `resources/views/admin/laporan-warga/kategori.blade.php`

**CATATAN:** Views admin bisa dibuat mengikuti pola yang sudah ada di folder `resources/views/admin/` yang lain (seperti berita, kategori, dll).

---

## 🎨 Customize

### Menambah Jenis Surat Baru

Akses admin panel: `/admin/jenis-surat` dan klik "Tambah Jenis Surat"

### Menambah Kategori Laporan

Akses admin panel: `/admin/kategori-laporan` dan klik "Tambah Kategori"

### Mengubah Nama Desa Default

Edit di `resources/views/pengajuan-surat/create.blade.php` baris:

```php
value="{{ old('desa_kelurahan', 'Karangduren') }}"
```

---

## 📧 Notifikasi (Opsional - Fitur Tambahan)

Untuk menambahkan email notification saat ada pengajuan/laporan baru:

1. Konfigurasi SMTP di `.env`
2. Buat Notification class
3. Tambahkan di controller setelah `create()`

---

## 🔒 Security & Validation

✅ CSRF Protection
✅ File Upload Validation (max 2MB)
✅ Image/PDF only
✅ XSS Protection
✅ SQL Injection Prevention (Eloquent ORM)

---

## 📊 Fitur Lanjutan yang Bisa Ditambahkan

1. **Email/SMS Notification** saat status berubah
2. **Export to PDF/Excel** untuk laporan
3. **Dashboard Statistics** (jumlah laporan per kategori, dll)
4. **Filter & Search** advanced
5. **Rating System** untuk pelayanan
6. **Integration dengan WhatsApp** untuk notifikasi

---

## 🆘 Troubleshooting

**Error: Storage not found**

```bash
php artisan storage:link
```

**Error: Class not found**

```bash
composer dump-autoload
```

**Error: Migration**

```bash
php artisan migrate:fresh --seed
```

---

## 📞 Support

Jika ada error atau pertanyaan, silakan:

1. Cek error di `storage/logs/laravel.log`
2. Pastikan semua migration sudah berjalan
3. Pastikan storage sudah ter-link
4. Cek permission folder storage (755/777)

---

**✨ Fitur Siap Digunakan!**

Akses:

-   Frontend: `http://localhost:8000/pengajuan-surat` dan `http://localhost:8000/laporan-warga`
-   Admin: `http://localhost:8000/admin/pengajuan-surat` dan `http://localhost:8000/admin/laporan-warga`
