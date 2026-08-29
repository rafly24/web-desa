# ⚡ QUICK START GUIDE

## 🚀 Instalasi Cepat (5 Langkah)

```bash
# 1. Jalankan Migration
php artisan migrate

# 2. Jalankan Seeder
php artisan db:seed --class=JenisSuratSeeder
php artisan db:seed --class=KategoriLaporanSeeder

# 3. Link Storage
php artisan storage:link

# 4. Clear Cache
php artisan cache:clear
php artisan config:clear

# 5. Start Server
php artisan serve
```

## 🌐 URL Quick Access

### Frontend

-   Pengajuan Surat: http://localhost:8000/pengajuan-surat
-   Laporan Warga: http://localhost:8000/laporan-warga

### Admin (Login: admin@gmail.com / 1234)

-   Pengajuan Surat: http://localhost:8000/admin/pengajuan-surat
-   Laporan Warga: http://localhost:8000/admin/laporan-warga

## 📋 Testing Checklist

### Pengajuan Surat

-   [ ] Buka halaman pengajuan surat
-   [ ] Pilih jenis surat (contoh: SKD)
-   [ ] Isi form lengkap
-   [ ] Upload KTP
-   [ ] Submit dan cek nomor pengajuan
-   [ ] Tracking status dengan nomor
-   [ ] Login admin dan update status
-   [ ] Upload surat jadi (PDF)
-   [ ] Cek di frontend apakah bisa download

### Laporan Warga

-   [ ] Buka halaman laporan warga
-   [ ] Klik buat laporan baru
-   [ ] Pilih kategori (contoh: Infrastruktur)
-   [ ] Isi detail laporan
-   [ ] Upload foto bukti
-   [ ] Submit dan cek nomor laporan
-   [ ] Tracking status dengan nomor
-   [ ] Login admin dan beri tanggapan
-   [ ] Upload foto tindak lanjut
-   [ ] Update status ke selesai

## 🔧 Troubleshooting Quick Fix

```bash
# Error migration
php artisan migrate:fresh --seed

# Error routes
php artisan route:clear
php artisan route:cache

# Error views
php artisan view:clear

# Error composer
composer dump-autoload

# Error permission (Linux/Mac)
chmod -R 775 storage bootstrap/cache

# Error permission (Windows - Run as Administrator)
icacls storage /grant Users:F /t
icacls bootstrap/cache /grant Users:F /t
```

## 📊 Sample Data untuk Testing

### Data Pengajuan Surat

```
NIK: 3201234567890001
Nama: Budi Santoso
Tempat Lahir: Jakarta
Tanggal Lahir: 1990-01-01
Jenis Kelamin: L
Alamat: Jl. Merdeka No. 123
RT/RW: 001/002
Desa: Karangduren
Kecamatan: Karangduren
Kabupaten: Karangduren
Pekerjaan: Wiraswasta
No. Telepon: 081234567890
Keperluan: Untuk melamar pekerjaan
```

### Data Laporan Warga

```
Nama: Ahmad Wijaya
Email: ahmad@example.com
No. Telepon: 081234567890
Alamat: Jl. Raya Desa No. 45
Kategori: Infrastruktur
Judul: Jalan Rusak di RT 02
Isi: Jalan di RT 02 RW 03 mengalami kerusakan parah...
Lokasi: Jl. Raya Desa RT 02/03
Tanggal Kejadian: [hari ini]
Prioritas: Sedang
```

## 🎯 Features Status

### ✅ Sudah Dibuat

-   [x] Migration (4 tabel)
-   [x] Models (4 models)
-   [x] Controllers (4 controllers)
-   [x] Frontend Views (7 views)
-   [x] Admin Views (4 views)
-   [x] Seeders (2 seeders)
-   [x] Routes
-   [x] Validation
-   [x] File Upload
-   [x] Status Tracking
-   [x] Auto Number Generation

### 📝 Perlu Dibuat Sendiri (Optional)

-   [ ] Views admin untuk kelola jenis surat
-   [ ] Views admin untuk kelola kategori laporan
-   [ ] Email notification
-   [ ] Export PDF
-   [ ] Advanced filter

## 💾 Backup Database

```bash
# Export database
mysqldump -u root -p desa-laravel > backup_with_new_features.sql

# Import database
mysql -u root -p desa-laravel < backup_with_new_features.sql
```

## 🎨 Customization

### Ubah Nama Desa

File: `resources/views/pengajuan-surat/create.blade.php`
Line: ~110

```php
value="{{ old('desa_kelurahan', 'Karangduren') }}"
// Ganti 'Karangduren' dengan nama desa Anda
```

### Tambah Jenis Surat

-   Login admin
-   Buka: http://localhost:8000/admin/jenis-surat
-   Klik "Tambah Jenis Surat"

### Tambah Kategori Laporan

-   Login admin
-   Buka: http://localhost:8000/admin/kategori-laporan
-   Klik "Tambah Kategori"

## 📱 Mobile Testing

Test responsiveness:

1. Buka browser developer tools (F12)
2. Toggle device toolbar (Ctrl+Shift+M)
3. Pilih device (iPhone, Samsung, dll)
4. Test semua fitur

## 🚀 Production Deployment

```bash
# 1. Set environment
cp .env .env.backup
nano .env
# Set APP_ENV=production
# Set APP_DEBUG=false

# 2. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# 4. Security
php artisan key:generate
```

## 📞 Need Help?

Check logs:

```bash
tail -f storage/logs/laravel.log
```

## ✨ Done!

Fitur siap digunakan untuk penelitian!

---

**Next Steps:**

1. Test semua fitur ✅
2. Screenshot untuk dokumentasi 📸
3. Tambah PWA notification (optional) 🔔
4. Deploy ke hosting 🚀
