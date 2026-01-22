<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriLaporan extends Model
{
    use HasFactory;

    protected $table = 'kategori_laporan';

    protected $fillable = [
        'nama_kategori',
        'icon',
        'warna',
        'deskripsi',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function laporanWarga()
    {
        return $this->hasMany(LaporanWarga::class);
    }
}
