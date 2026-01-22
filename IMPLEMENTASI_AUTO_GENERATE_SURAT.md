# IMPLEMENTASI AUTO-GENERATE SURAT PDF

## 📋 Konsep & Alur Baru

### Alur Lama (Manual):

1. Warga ajukan → 2. Admin approve → 3. Admin upload PDF manual → 4. Warga download

### Alur Baru (Auto-Generate):

1. **Warga ajukan** surat online
2. **Admin review** data, jika benar admin approve & **input nomor surat**
3. **Sistem auto-generate** PDF dari template
4. **Warga download & cetak** sendiri

## 🎯 Keuntungan Sistem Baru:

✅ **Lebih Cepat** - Tidak perlu buat surat manual di Word/Excel
✅ **Konsisten** - Semua surat format sama, tidak ada typo
✅ **Efisien** - Admin hanya input nomor surat, sistem yang generate
✅ **Real-time** - Begitu approve, warga langsung bisa download
✅ **Paperless** - Warga cetak sendiri, tidak perlu ke kantor desa

## 📁 File Yang Sudah Dibuat

### 1. Migration

```
database/migrations/2024_12_16_000003_add_nomor_surat_to_pengajuan_surat_table.php
```

-   Menambah kolom `nomor_surat` untuk menyimpan nomor yang diinput admin

### 2. Template Surat (Blade)

```
resources/views/templates/surat/skd.blade.php   - Surat Keterangan Domisili
resources/views/templates/surat/sku.blade.php   - Surat Keterangan Usaha
resources/views/templates/surat/sktm.blade.php  - Surat Keterangan Tidak Mampu
```

**Format Template:**

-   Header: Kop surat desa dengan logo
-   Body: Data pemohon otomatis dari database
-   Footer: TTD Kepala Desa (digital)
-   Watermark: Timestamp dan info elektronik

### 3. Controller Update

```
app/Http/Controllers/AdminPengajuanSuratController.php
```

-   Method `updateStatus()` - Update untuk validasi nomor surat
-   Method `generateSuratPDF()` - Generate PDF dari template

### 4. Model Update

```
app/Models/PengajuanSurat.php
```

-   Tambah `nomor_surat` ke fillable

## 🚀 Cara Install

### Step 1: Jalankan Migration

```bash
php artisan migrate
```

### Step 2: Install Package DomPDF

```bash
composer require barryvdh/laravel-dompdf
```

### Step 3: Publish Config (Optional)

```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

### Step 4: Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
```

## 📝 Cara Menggunakan (Admin)

### 1. Lihat Pengajuan Baru

-   Masuk ke `/admin/pengajuan-surat`
-   Klik "Detail" pada pengajuan yang status "Pending"

### 2. Review Data Pemohon

-   Periksa data NIK, nama, alamat, dll
-   Lihat file upload (KTP, KK, dll) jika ada
-   Pastikan data sudah benar

### 3. Approve & Input Nomor Surat

-   Ubah status ke "Selesai"
-   **Input Nomor Surat** (WAJIB):

    ```
    Format: 474/001/XII/2024

    Keterangan:
    - 474 = Kode surat (sesuai jenis)
    - 001 = Nomor urut
    - XII = Bulan romawi
    - 2024 = Tahun
    ```

-   Klik "Update Status"

### 4. Sistem Auto-Generate PDF

-   Sistem otomatis pilih template sesuai jenis surat
-   Generate PDF dengan data pemohon
-   Simpan di `storage/app/public/surat-jadi/`
-   Status berubah "Selesai" ✅

### 5. Warga Download

-   Warga masuk dashboard
-   Lihat status "Selesai"
-   Klik tombol "Download PDF"
-   Cetak sendiri di rumah

## 🎨 Customize Template

### Lokasi Template:

```
resources/views/templates/surat/
```

### Cara Edit:

1. Buka file template (contoh: `skd.blade.php`)
2. Edit HTML/CSS sesuai kebutuhan
3. Variabel yang tersedia:
    ```blade
    {{ $nomor_surat }}                    // Nomor surat dari admin
    {{ $pengajuan->nama_lengkap }}       // Nama pemohon
    {{ $pengajuan->nik }}                // NIK
    {{ $pengajuan->tempat_lahir }}       // Tempat lahir
    {{ $pengajuan->tanggal_lahir }}      // Tanggal lahir
    {{ $pengajuan->jenis_kelamin }}      // L/P
    {{ $pengajuan->alamat }}             // Alamat
    {{ $pengajuan->rt_rw }}              // RT/RW
    {{ $pengajuan->keperluan }}          // Keperluan surat
    // dll...
    ```

### Edit Kop Surat:

```blade
<div class="kop-surat">
    <h2>PEMERINTAH KABUPATEN [NAMA KABUPATEN]</h2>
    <h3>KECAMATAN [NAMA KECAMATAN]</h3>
    <h3>DESA [NAMA DESA]</h3>
    <p>Alamat: [ALAMAT LENGKAP DESA]</p>
    <p>Email: [EMAIL] | Telp: [TELEPON]</p>
</div>
```

### Edit TTD:

```blade
<div class="ttd">
    <p>Karangduren, {{ $tanggal }}</p>
    <p><strong>Kepala Desa Karangduren</strong></p>
    <div class="ttd-space"></div>
    <p><strong><u>[NAMA KEPALA DESA]</u></strong></p>
    <p>NIP. [NIP KEPALA DESA]</p>
</div>
```

## 🔧 Troubleshooting

### Error: "Class 'PDF' not found"

**Solusi:** Install dompdf package

```bash
composer require barryvdh/laravel-dompdf
```

### Error: "Template not found"

**Solusi:** Cek nama file template harus sama dengan kode_surat (lowercase)

```
SKD  → skd.blade.php
SKU  → sku.blade.php
SKTM → sktm.blade.php
```

### PDF Tidak Generate

**Solusi:**

1. Cek permission folder storage

```bash
chmod -R 775 storage/
```

2. Buat storage link

```bash
php artisan storage:link
```

### Format Nomor Surat Salah

**Contoh Format yang Benar:**

```
474/001/XII/2024
470.1/005/I/2025
145.3/SKD/024/XI/2024
```

## 📊 Mapping Kode Surat

| Kode Surat | Nama Surat                   | Template File   | Kode Angka |
| ---------- | ---------------------------- | --------------- | ---------- |
| SKD        | Surat Keterangan Domisili    | skd.blade.php   | 470.1      |
| SKU        | Surat Keterangan Usaha       | sku.blade.php   | 503        |
| SKTM       | Surat Keterangan Tidak Mampu | sktm.blade.php  | 470.2      |
| SPKTP      | Surat Pengantar KTP          | spktp.blade.php | 474        |
| SPKK       | Surat Pengantar KK           | spkk.blade.php  | 474        |

## 🎯 TODO: Template Tambahan

Untuk jenis surat lainnya, buat template baru:

1. Copy template existing (misal `skd.blade.php`)
2. Rename sesuai kode surat (lowercase)
3. Edit isi surat sesuai kebutuhan
4. Save di folder `resources/views/templates/surat/`

Contoh membuat template baru untuk SPKTP:

```bash
# Copy template
cp resources/views/templates/surat/skd.blade.php resources/views/templates/surat/spktp.blade.php

# Edit sesuai kebutuhan
nano resources/views/templates/surat/spktp.blade.php
```

## 📌 Catatan Penting

1. **Nomor Surat WAJIB** diisi saat status "Selesai"
2. **Template** harus ada untuk jenis surat yang dipilih
3. **PDF** otomatis tersimpan di `storage/app/public/surat-jadi/`
4. **Warga** bisa download berkali-kali (link permanen)
5. **Admin** tidak perlu upload PDF manual lagi

## 🔐 Security

-   PDF hanya bisa diakses oleh warga yang mengajukan (via dashboard)
-   File tersimpan dengan nama unik: `surat-PGJ-20241216-0001.pdf`
-   Link download memerlukan autentikasi

---

**Update:** 16 Desember 2024
**Oleh:** GitHub Copilot Assistant
**Status:** ✅ Ready to Use (after install dompdf)
