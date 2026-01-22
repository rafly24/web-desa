<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisSurat = [
            [
                'nama_surat' => 'Surat Keterangan Domisili',
                'kode_surat' => 'SKD',
                'persyaratan' => "1. Fotocopy KTP\n2. Fotocopy Kartu Keluarga\n3. Surat Pengantar RT/RW",
                'keterangan' => 'Surat keterangan domisili untuk berbagai keperluan administrasi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_surat' => 'Surat Keterangan Usaha',
                'kode_surat' => 'SKU',
                'persyaratan' => "1. Fotocopy KTP\n2. Fotocopy Kartu Keluarga\n3. Foto tempat usaha",
                'keterangan' => 'Surat keterangan untuk usaha mikro, kecil, dan menengah (UMKM)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_surat' => 'Surat Pengantar KTP',
                'kode_surat' => 'SPKTP',
                'persyaratan' => "1. Fotocopy Kartu Keluarga\n2. Surat Pengantar RT/RW\n3. Pas foto 4x6 (2 lembar)",
                'keterangan' => 'Surat pengantar untuk pembuatan KTP baru atau perpanjangan',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_surat' => 'Surat Pengantar Kartu Keluarga',
                'kode_surat' => 'SPKK',
                'persyaratan' => "1. Fotocopy KTP suami istri\n2. Fotocopy Kartu Keluarga lama\n3. Fotocopy Akta Nikah/Cerai\n4. Surat Pengantar RT/RW",
                'keterangan' => 'Surat pengantar untuk pembuatan atau perubahan Kartu Keluarga',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_surat' => 'Surat Keterangan Tidak Mampu (SKTM)',
                'kode_surat' => 'SKTM',
                'persyaratan' => "1. Fotocopy KTP\n2. Fotocopy Kartu Keluarga\n3. Surat Pengantar RT/RW\n4. Keterangan penghasilan dari RT/RW",
                'keterangan' => 'Surat keterangan untuk bantuan pendidikan, kesehatan, atau lainnya',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_surat' => 'Surat Keterangan Kelahiran',
                'kode_surat' => 'SKL',
                'persyaratan' => "1. Fotocopy KTP orang tua\n2. Fotocopy Kartu Keluarga\n3. Fotocopy Akta Nikah\n4. Surat keterangan lahir dari bidan/rumah sakit",
                'keterangan' => 'Surat keterangan untuk pengurusan akta kelahiran',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_surat' => 'Surat Keterangan Pindah',
                'kode_surat' => 'SKP',
                'persyaratan' => "1. Fotocopy KTP\n2. Fotocopy Kartu Keluarga\n3. Surat Pengantar RT/RW",
                'keterangan' => 'Surat keterangan untuk pindah domisili ke wilayah lain',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('jenis_surat')->insert($jenisSurat);
    }
}
