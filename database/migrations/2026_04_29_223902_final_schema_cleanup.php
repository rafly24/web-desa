<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Bersihkan kolom sisa di pengajuan_surat
        if (Schema::hasColumn('pengajuan_surat', 'catatan_admin')) {
            Schema::table('pengajuan_surat', function (Blueprint $table) {
                $table->dropColumn('catatan_admin');
            });
        }

        // 2. Bersihkan kolom sisa di laporan_warga
        Schema::table('laporan_warga', function (Blueprint $table) {
            if (Schema::hasColumn('laporan_warga', 'tanggapan_admin')) {
                $table->dropColumn('tanggapan_admin');
            }
            if (Schema::hasColumn('laporan_warga', 'foto_tindak_lanjut')) {
                $table->dropColumn('foto_tindak_lanjut');
            }
        });

        // 3. Bersihkan kolom data diri warga dari tabel users (Kecuali ID, Nama, Email, Password, Role)
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = ['nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'rt_rw', 'no_telepon'];
            foreach ($columnsToDrop as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        // 4. Tambahkan relasi Foreign Key untuk tabel-tabel master konten agar tampil di ERD
        // Array nama tabel konten yang diketahui memiliki kolom user_id
        $tablesWithUserId = [
            'layanans', 'pekerjaans', 'petas', 'sejarahs', 'visi_misis',
            'sliders', 'agamas', 'jenis_kelamins', 'perangkat_desas',
            'kategori_beritas', 'beritas', 'umkms', 'pengumumans', 'galleries',
            'kategori_laporans', 'jenis_surats', 'anggarans', 'situs', 'kontaks'
        ];

        foreach ($tablesWithUserId as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'user_id')) {
                try {
                    // Gunakan raw DB statement agar exception PDO/QueryException bisa ditangkap dengan benar
                    // jika ternyata kolom user_id bertipe 'integer' biasa, bukan 'unsignedBigInteger'
                    \Illuminate\Support\Facades\DB::statement("ALTER TABLE `{$tableName}` ADD CONSTRAINT `{$tableName}_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;");
                } catch (\Exception $e) {
                    // Abaikan error jika tipe data tidak cocok atau relasi sudah ada
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ... (Tidak perlu reverse komprehensif untuk cleanup satu arah)
    }
};
