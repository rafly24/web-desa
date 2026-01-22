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
        Schema::create('laporan_warga', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_laporan')->unique();
            $table->foreignId('kategori_laporan_id')->constrained('kategori_laporan')->onDelete('cascade');
            $table->string('nama_pelapor');
            $table->string('email')->nullable();
            $table->string('no_telepon', 15);
            $table->string('alamat');
            $table->string('judul_laporan');
            $table->text('isi_laporan');
            $table->string('lokasi_kejadian');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->date('tanggal_kejadian');
            $table->json('foto_bukti')->nullable(); // array foto
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi'])->default('sedang');
            $table->enum('status', ['baru', 'diproses', 'ditindaklanjuti', 'selesai', 'ditolak'])->default('baru');
            $table->text('tanggapan_admin')->nullable();
            $table->string('foto_tindak_lanjut')->nullable();
            $table->timestamp('tanggal_ditanggapi')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->foreignId('ditangani_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('views')->default(0);
            $table->boolean('is_anonim')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_warga');
    }
};
