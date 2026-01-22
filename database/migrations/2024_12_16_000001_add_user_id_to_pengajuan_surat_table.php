<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            
            // Jadikan field data pemohon nullable karena akan diambil dari user profile
            $table->string('nik', 16)->nullable()->change();
            $table->string('nama_lengkap')->nullable()->change();
            $table->string('no_telepon', 15)->nullable()->change();
            $table->text('alamat')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
