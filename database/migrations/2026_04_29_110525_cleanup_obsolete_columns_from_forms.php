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
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'rt_rw',
                'desa_kelurahan',
                'kecamatan',
                'kabupaten',
                'no_telepon'
            ]);
        });

        Schema::table('laporan_warga', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'alamat',
                'judul_laporan',
                'lokasi_kejadian',
                'tanggal_kejadian',
                'prioritas',
                'is_anonim'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reversing this would recreate the columns, but for schema cleanup we don't strictly need it.
        // We will just leave it empty or partially implemented.
    }
};
