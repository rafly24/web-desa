<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriLaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            [
                'nama_kategori' => 'Infrastruktur',
                'icon' => 'tools',
                'warna' => '#dc3545',
                'deskripsi' => 'Laporan terkait jalan, jembatan, dan infrastruktur desa',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Kebersihan & Lingkungan',
                'icon' => 'trash',
                'warna' => '#28a745',
                'deskripsi' => 'Laporan terkait kebersihan, sampah, dan lingkungan',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Pelayanan Publik',
                'icon' => 'people',
                'warna' => '#007bff',
                'deskripsi' => 'Laporan terkait pelayanan administrasi dan layanan desa',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Keamanan & Ketertiban',
                'icon' => 'shield-check',
                'warna' => '#fd7e14',
                'deskripsi' => 'Laporan terkait keamanan dan ketertiban lingkungan',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Kesehatan',
                'icon' => 'heart-pulse',
                'warna' => '#e83e8c',
                'deskripsi' => 'Laporan terkait kesehatan masyarakat dan sanitasi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Pendidikan',
                'icon' => 'book',
                'warna' => '#6f42c1',
                'deskripsi' => 'Laporan terkait fasilitas dan layanan pendidikan',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Lainnya',
                'icon' => 'three-dots',
                'warna' => '#6c757d',
                'deskripsi' => 'Laporan lainnya yang tidak masuk kategori di atas',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('kategori_laporan')->insert($kategori);
    }
}
