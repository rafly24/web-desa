<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('warga')->after('email'); // admin / warga
            $table->string('nik', 16)->nullable()->after('role');
            $table->string('tempat_lahir')->nullable()->after('nik');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable()->after('tanggal_lahir');
            $table->text('alamat')->nullable()->after('jenis_kelamin');
            $table->string('rt_rw', 10)->nullable()->after('alamat');
            $table->string('no_telepon', 15)->nullable()->after('rt_rw');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'nik', 'tempat_lahir', 'tanggal_lahir', 
                'jenis_kelamin', 'alamat', 'rt_rw', 'no_telepon'
            ]);
        });
    }
};
