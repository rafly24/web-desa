<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSurat extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_surat';

    protected $fillable = [
        'user_id',
        'nomor_pengajuan',
        'nomor_surat',
        'jenis_surat_id',
        'nik',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'rt_rw',
        'desa_kelurahan',
        'kecamatan',
        'kabupaten',
        'pekerjaan',
        'no_telepon',
        'keperluan',
        'data_tambahan',
        'file_ktp',
        'file_kk',
        'file_pendukung',
        'status',
        'catatan_admin',
        'file_surat_jadi',
        'tanggal_diproses',
        'tanggal_selesai',
        'diproses_oleh'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_diproses' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'data_tambahan' => 'array',
    ];

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'diproses' => '<span class="badge bg-info">Diproses</span>',
            'selesai' => '<span class="badge bg-success">Selesai</span>',
            'ditolak' => '<span class="badge bg-danger">Ditolak</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->nomor_pengajuan)) {
                $model->nomor_pengajuan = 'PGJ-' . date('Ymd') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
