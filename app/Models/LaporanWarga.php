<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanWarga extends Model
{
    use HasFactory;

    protected $table = 'laporan_warga';

    protected $fillable = [
        'nomor_laporan',
        'kategori_laporan_id',
        'nama_pelapor',
        'no_telepon',
        'isi_laporan',
        'latitude',
        'longitude',
        'foto_bukti',
        'status',
        'tanggal_ditanggapi',
        'tanggal_selesai',
        'ditangani_oleh',
        'views'
    ];

    protected $casts = [
        'tanggal_ditanggapi' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'foto_bukti' => 'array',
    ];

    public function kategoriLaporan()
    {
        return $this->belongsTo(KategoriLaporan::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'ditangani_oleh');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'baru' => '<span class="badge bg-primary">Baru</span>',
            'diproses' => '<span class="badge bg-info">Diproses</span>',
            'ditindaklanjuti' => '<span class="badge bg-warning">Ditindaklanjuti</span>',
            'selesai' => '<span class="badge bg-success">Selesai</span>',
            'ditolak' => '<span class="badge bg-danger">Ditolak</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->nomor_laporan)) {
                $model->nomor_laporan = 'LPR-' . date('Ymd') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
