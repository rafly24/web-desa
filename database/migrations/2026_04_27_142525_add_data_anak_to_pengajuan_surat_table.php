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
            $table->string('nama_anak')->nullable();
            $table->enum('jenis_kelamin_anak', ['L', 'P'])->nullable();
            $table->string('tempat_lahir_anak')->nullable();
            $table->date('tanggal_lahir_anak')->nullable();
            $table->string('nik_anak', 16)->nullable();
            $table->string('pekerjaan_anak')->nullable();
            $table->text('alamat_anak')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            $table->dropColumn([
                'nama_anak',
                'jenis_kelamin_anak',
                'tempat_lahir_anak',
                'tanggal_lahir_anak',
                'nik_anak',
                'pekerjaan_anak',
                'alamat_anak'
            ]);
        });
    }
};
