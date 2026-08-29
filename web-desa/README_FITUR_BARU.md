# 🎉 FITUR BARU: PENGAJUAN SURAT ONLINE & LAPORAN WARGA

Fitur telah berhasil dibuat untuk penelitian **"Pengembangan Sistem Informasi Desa Karangduren Berbasis PWA Menggunakan Metode Waterfall"**

---

## ✅ FITUR YANG TELAH DIBUAT

### 1. 📄 **PENGAJUAN SURAT ONLINE**

**Fitur Utama:**

-   ✔️ Form pengajuan online untuk 7 jenis surat:

    -   Surat Keterangan Domisili (SKD)
    -   Surat Keterangan Usaha (SKU)
    -   Surat Pengantar KTP (SPKTP)
    -   Surat Pengantar KK (SPKK)
    -   Surat Keterangan Tidak Mampu (SKTM)
    -   Surat Keterangan Kelahiran (SKL)
    -   Surat Keterangan Pindah (SKP)

-   ✔️ Upload dokumen: KTP, KK, Dokumen Pendukung
-   ✔️ Nomor pengajuan otomatis (format: PGJ-YYYYMMDD-XXXX)
-   ✔️ Tracking status real-time dengan timeline
-   ✔️ 4 Status: Pending → Diproses → Selesai/Ditolak
-   ✔️ Download surat jadi (PDF) setelah selesai
-   ✔️ Catatan admin untuk setiap pengajuan

**Admin Panel:**

-   ✔️ Dashboard statistik pengajuan
-   ✔️ Detail lengkap pengajuan + dokumen
-   ✔️ Update status pengajuan
-   ✔️ Upload surat jadi
-   ✔️ Kelola jenis surat (CRUD)

---

### 2. 🚨 **LAPORAN WARGA / COMPLAINT SYSTEM**

**Fitur Utama:**

-   ✔️ Form laporan dengan 7 kategori:

    -   Infrastruktur
    -   Kebersihan & Lingkungan
    -   Pelayanan Publik
    -   Keamanan & Ketertiban
    -   Kesehatan
    -   Pendidikan
    -   Lainnya

-   ✔️ Upload foto bukti (multiple photos)
-   ✔️ Nomor laporan otomatis (format: LPR-YYYYMMDD-XXXX)
-   ✔️ 3 Level prioritas: Rendah, Sedang, Tinggi
-   ✔️ 5 Status: Baru → Diproses → Ditindaklanjuti → Selesai/Ditolak
-   ✔️ Opsi laporan anonim (nama tidak ditampilkan)
-   ✔️ Koordinat GPS untuk pemetaan (opsional)
-   ✔️ View counter
-   ✔️ Tracking status dengan timeline

**Admin Panel:**

-   ✔️ Dashboard statistik laporan
-   ✔️ Detail lengkap laporan + foto bukti
-   ✔️ Tanggapan/response admin
-   ✔️ Upload foto tindak lanjut
-   ✔️ Update status & prioritas
-   ✔️ Peta lokasi (jika ada koordinat)
-   ✔️ Kelola kategori laporan (CRUD)

---

## 📊 DATABASE SCHEMA

**4 Tabel Baru:**

1. **jenis_surat** (Master data jenis surat)
2. **pengajuan_surat** (Data pengajuan surat warga)
3. **kategori_laporan** (Master kategori laporan)
4. **laporan_warga** (Data laporan warga)

---

## 🚀 CARA INSTALASI

### **Step 1: Jalankan Migration**

```bash
php artisan migrate
```

### **Step 2: Jalankan Seeder**

```bash
php artisan db:seed --class=JenisSuratSeeder
php artisan db:seed --class=KategoriLaporanSeeder
```

### **Step 3: Buat Symbolic Link Storage**

```bash
php artisan storage:link
```

### **Step 4: Clear Cache (Opsional)**

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
composer dump-autoload
```

---

## 🌐 URL AKSES

### **FRONTEND (Untuk Warga)**

**Pengajuan Surat:**

-   Daftar Jenis Surat: `http://localhost:8000/pengajuan-surat`
-   Form Pengajuan: `http://localhost:8000/pengajuan-surat/create/{kode_surat}`
-   Tracking Status: `http://localhost:8000/pengajuan-surat/tracking`

**Laporan Warga:**

-   Daftar Laporan: `http://localhost:8000/laporan-warga`
-   Buat Laporan: `http://localhost:8000/laporan-warga/create`
-   Detail Laporan: `http://localhost:8000/laporan-warga/{id}`
-   Tracking Status: `http://localhost:8000/laporan-warga/tracking`

### **ADMIN PANEL (Setelah Login)**

**Pengajuan Surat:**

-   Daftar Pengajuan: `http://localhost:8000/admin/pengajuan-surat`
-   Detail & Update: `http://localhost:8000/admin/pengajuan-surat/{id}`
-   Kelola Jenis Surat: `http://localhost:8000/admin/jenis-surat`

**Laporan Warga:**

-   Daftar Laporan: `http://localhost:8000/admin/laporan-warga`
-   Detail & Tanggapan: `http://localhost:8000/admin/laporan-warga/{id}`
-   Kelola Kategori: `http://localhost:8000/admin/kategori-laporan`

---

## 📁 STRUKTUR FILE

```
📦 web-desa/
├── 📂 app/
│   ├── 📂 Http/Controllers/
│   │   ├── PengajuanSuratController.php (Frontend)
│   │   ├── AdminPengajuanSuratController.php (Admin)
│   │   ├── LaporanWargaController.php (Frontend)
│   │   └── AdminLaporanWargaController.php (Admin)
│   └── 📂 Models/
│       ├── JenisSurat.php
│       ├── PengajuanSurat.php
│       ├── KategoriLaporan.php
│       └── LaporanWarga.php
├── 📂 database/
│   ├── 📂 migrations/
│   │   ├── 2024_12_15_000001_create_jenis_surat_table.php
│   │   ├── 2024_12_15_000002_create_pengajuan_surat_table.php
│   │   ├── 2024_12_15_000003_create_kategori_laporan_table.php
│   │   └── 2024_12_15_000004_create_laporan_warga_table.php
│   └── 📂 seeders/
│       ├── JenisSuratSeeder.php
│       └── KategoriLaporanSeeder.php
├── 📂 resources/views/
│   ├── 📂 pengajuan-surat/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── tracking.blade.php
│   ├── 📂 laporan-warga/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── detail.blade.php
│   │   └── tracking.blade.php
│   └── 📂 admin/
│       ├── 📂 pengajuan-surat/
│       │   ├── index.blade.php
│       │   └── show.blade.php
│       └── 📂 laporan-warga/
│           ├── index.blade.php
│           └── show.blade.php
└── 📂 routes/
    └── web.php (sudah ditambahkan routes baru)
```

---

## 🎯 CARA PENGGUNAAN

### **UNTUK WARGA:**

1. **Pengajuan Surat:**

    - Buka menu "Pengajuan Surat"
    - Pilih jenis surat yang diinginkan
    - Isi formulir lengkap
    - Upload KTP (wajib)
    - Submit → Dapatkan nomor pengajuan
    - Tracking dengan nomor pengajuan

2. **Laporan Warga:**
    - Buka menu "Laporan Warga"
    - Klik "Buat Laporan Baru"
    - Pilih kategori laporan
    - Isi detail laporan
    - Upload foto bukti (opsional)
    - Centang "Anonim" jika tidak ingin nama ditampilkan
    - Submit → Dapatkan nomor laporan
    - Tracking dengan nomor laporan

### **UNTUK ADMIN:**

1. **Kelola Pengajuan Surat:**

    - Login ke admin panel
    - Buka "Pengajuan Surat"
    - Lihat daftar pengajuan dengan statistik
    - Klik "Detail" untuk melihat data lengkap
    - Update status (Pending → Diproses → Selesai)
    - Upload surat jadi (PDF)
    - Beri catatan jika perlu

2. **Kelola Laporan Warga:**
    - Login ke admin panel
    - Buka "Laporan Warga"
    - Lihat daftar laporan dengan statistik
    - Klik "Detail" untuk melihat laporan lengkap
    - Beri tanggapan/response
    - Update status (Baru → Diproses → Ditindaklanjuti → Selesai)
    - Upload foto tindak lanjut
    - Ubah prioritas jika diperlukan

---

## 🔐 LOGIN ADMIN

```
Email: admin@gmail.com
Password: 1234
```

---

## ⚙️ KONFIGURASI

### **Upload File Settings:**

-   Max file size: 2MB (gambar), 5MB (PDF surat jadi)
-   Format: JPG, PNG, PDF
-   Storage: `storage/app/public/`

### **Nomor Otomatis:**

-   Pengajuan: `PGJ-YYYYMMDD-XXXX`
-   Laporan: `LPR-YYYYMMDD-XXXX`
-   Auto increment per hari

---

## 📸 FITUR TAMBAHAN

### **Yang Sudah Ada:**

✅ Auto-generate nomor pengajuan/laporan
✅ Timeline tracking visual
✅ Badge status berwarna
✅ Multiple file upload
✅ Image preview
✅ Responsive design
✅ SweetAlert notifications
✅ Data validation
✅ Security (CSRF, XSS protection)

### **Yang Bisa Ditambahkan:**

-   [ ] Email notification
-   [ ] WhatsApp integration
-   [ ] SMS notification
-   [ ] Export to PDF/Excel
-   [ ] Advanced filter & search
-   [ ] Rating system
-   [ ] QR Code untuk tracking
-   [ ] PWA push notifications

---

## 🐛 TROUBLESHOOTING

**1. Error 404 Not Found**

```bash
php artisan route:clear
php artisan cache:clear
```

**2. Error Class Not Found**

```bash
composer dump-autoload
```

**3. Error Storage Link**

```bash
php artisan storage:link
```

**4. Migration Error**

```bash
php artisan migrate:fresh --seed
```

**5. Permission Error (Linux)**

```bash
chmod -R 777 storage
chmod -R 777 bootstrap/cache
```

---

## 📞 SUPPORT

Jika ada pertanyaan atau error:

1. Cek `storage/logs/laravel.log`
2. Pastikan semua migration sudah running
3. Pastikan seeder sudah dijalankan
4. Pastikan storage sudah di-link

---

## 📝 CATATAN UNTUK PENELITIAN

Fitur ini dibuat untuk mendukung penelitian dengan fokus:

1. **PWA (Progressive Web App):**

    - Service Worker sudah ada
    - Manifest sudah ada
    - Tinggal tambahkan push notification

2. **Metode Waterfall:**

    - ✅ Requirements: Pengajuan Surat & Laporan Warga
    - ✅ Design: Database schema, UI/UX
    - ✅ Implementation: Backend + Frontend
    - ✅ Testing: Manual testing siap
    - ⏳ Maintenance: Deployment & monitoring

3. **Citizen Engagement:**
    - Transparansi pelayanan publik
    - Partisipasi warga dalam pembangunan
    - Monitoring real-time

---

## 🎓 DOKUMENTASI PENELITIAN

Untuk keperluan skripsi/tesis, dokumentasikan:

-   Screenshots setiap halaman
-   Flow diagram user journey
-   Database ER Diagram
-   Testing results
-   User feedback

---

**🎉 SELAMAT! Fitur sudah siap digunakan dan dikembangkan lebih lanjut!**

---

_Dibuat dengan ❤️ untuk Penelitian Sistem Informasi Desa Karangduren_
