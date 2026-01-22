<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            // Tambah field nomor_surat yang akan diinput admin saat approve
            $table->string('nomor_surat')->nullable()->after('nomor_pengajuan');
            
            // Hapus field file_surat_jadi karena akan auto-generate dari template
            // $table->dropColumn('file_surat_jadi'); // Uncomment jika ingin hapus
        });
    }

    public function down()
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            $table->dropColumn('nomor_surat');
        });
    }
};
