<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    use HasFactory;

    protected $table = 'jenis_surat';

    protected $fillable = [
        'nama_surat',
        'kode_surat',
        'persyaratan',
        'keterangan',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function pengajuanSurat()
    {
        return $this->hasMany(PengajuanSurat::class);
    }
}
