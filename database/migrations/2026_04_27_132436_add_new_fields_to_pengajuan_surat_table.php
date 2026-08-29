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
            $table->string('agama')->nullable()->after('pekerjaan');
            $table->string('kebangsaan')->nullable()->after('agama');
            $table->string('status_perkawinan')->nullable()->after('kebangsaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            $table->dropColumn(['agama', 'kebangsaan', 'status_perkawinan']);
        });
    }
};
