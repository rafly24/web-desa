@extends('layouts.main')

@section('content')
<section id="breadcrumbs" class="breadcrumbs">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <h2>Tracking Laporan</h2>
      <ol>
        <li><a href="/">Beranda</a></li>
        <li><a href="{{ route('laporan-warga.index') }}">Laporan Warga</a></li>
        <li>Tracking</li>
      </ol>
    </div>
  </div>
</section>

<section class="inner-page pt-4">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        
        <!-- Search Box -->
        <div class="card mb-4 shadow-sm">
          <div class="card-body">
            <h5 class="card-title mb-3"><i class="bi bi-search"></i> Cek Status Laporan</h5>
            <form action="{{ route('laporan-warga.cek-status') }}" method="POST">
              @csrf
              <div class="input-group">
                <input type="text" name="nomor_laporan" class="form-control" 
                       placeholder="Masukkan nomor laporan (contoh: LPR-20241215-0001)" 
                       value="{{ request('nomor') }}" required>
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-search"></i> Cek Status
                </button>
              </div>
            </form>
          </div>
        </div>

        @if($laporan)
        <!-- Result Box -->
        <div class="card shadow">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-megaphone"></i> Detail Laporan</h5>
          </div>
          <div class="card-body">
            
            <div class="row mb-3">
              <div class="col-md-6">
                <strong>Nomor Laporan:</strong><br>
                <span class="text-primary fs-5">{{ $laporan->nomor_laporan }}</span>
              </div>
              <div class="col-md-6 text-md-end">
                <strong>Status:</strong><br>
                {!! $laporan->status_badge !!}
              </div>
            </div>

            <hr>

            <div class="row mb-2">
              <div class="col-md-4"><strong>Kategori:</strong></div>
              <div class="col-md-8">
                <span class="badge" style="background-color: {{ $laporan->kategoriLaporan->warna }}">
                  {{ $laporan->kategoriLaporan->nama_kategori }}
                </span>
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-md-4"><strong>Judul Laporan:</strong></div>
              <div class="col-md-8">{{ $laporan->judul_laporan }}</div>
            </div>

            <div class="row mb-2">
              <div class="col-md-4"><strong>Nama Pelapor:</strong></div>
              <div class="col-md-8">{{ $laporan->is_anonim ? 'Anonim' : $laporan->nama_pelapor }}</div>
            </div>

            <div class="row mb-2">
              <div class="col-md-4"><strong>Lokasi Kejadian:</strong></div>
              <div class="col-md-8">{{ $laporan->lokasi_kejadian }}</div>
            </div>

            <div class="row mb-2">
              <div class="col-md-4"><strong>Tanggal Laporan:</strong></div>
              <div class="col-md-8">{{ $laporan->created_at->format('d F Y H:i') }}</div>
            </div>

            <div class="row mb-2">
              <div class="col-md-4"><strong>Prioritas:</strong></div>
              <div class="col-md-8">{!! $laporan->prioritas_badge !!}</div>
            </div>

            @if($laporan->tanggal_ditanggapi)
            <div class="row mb-2">
              <div class="col-md-4"><strong>Tanggal Ditanggapi:</strong></div>
              <div class="col-md-8">{{ $laporan->tanggal_ditanggapi->format('d F Y H:i') }}</div>
            </div>
            @endif

            @if($laporan->tanggal_selesai)
            <div class="row mb-2">
              <div class="col-md-4"><strong>Tanggal Selesai:</strong></div>
              <div class="col-md-8">{{ $laporan->tanggal_selesai->format('d F Y H:i') }}</div>
            </div>
            @endif

            @if($laporan->tanggapan_admin)
            <hr>
            <div class="alert alert-success">
              <strong><i class="bi bi-check-circle"></i> Tanggapan:</strong><br>
              {{ $laporan->tanggapan_admin }}
            </div>
            @endif

            <!-- Timeline -->
            <hr class="mt-4">
            <h6 class="mb-3">Timeline Proses</h6>
            <div class="timeline">
              <div class="timeline-item completed">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                  <h6>Laporan Diterima</h6>
                  <p class="text-muted small mb-0">{{ $laporan->created_at->format('d M Y H:i') }}</p>
                </div>
              </div>

              <div class="timeline-item {{ in_array($laporan->status, ['diproses', 'ditindaklanjuti', 'selesai']) ? 'completed' : 'pending' }}">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                  <h6>Sedang Diproses</h6>
                  @if($laporan->tanggal_ditanggapi && in_array($laporan->status, ['diproses', 'ditindaklanjuti', 'selesai']))
                    <p class="text-muted small mb-0">{{ $laporan->tanggal_ditanggapi->format('d M Y H:i') }}</p>
                  @endif
                </div>
              </div>

              <div class="timeline-item {{ in_array($laporan->status, ['ditindaklanjuti', 'selesai']) ? 'completed' : 'pending' }}">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                  <h6>Ditindaklanjuti</h6>
                </div>
              </div>

              <div class="timeline-item {{ $laporan->status === 'selesai' ? 'completed' : ($laporan->status === 'ditolak' ? 'rejected' : 'pending') }}">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                  <h6>{{ $laporan->status === 'ditolak' ? 'Ditolak' : 'Selesai' }}</h6>
                  @if($laporan->tanggal_selesai)
                    <p class="text-muted small mb-0">{{ $laporan->tanggal_selesai->format('d M Y H:i') }}</p>
                  @endif
                </div>
              </div>
            </div>

            <div class="text-center mt-4">
              <a href="{{ route('laporan-warga.detail', $laporan->id) }}" class="btn btn-primary">
                <i class="bi bi-eye"></i> Lihat Detail Lengkap
              </a>
            </div>

          </div>
        </div>

        @elseif(request('nomor'))
        <div class="alert alert-warning text-center">
          <i class="bi bi-exclamation-triangle"></i> Nomor laporan tidak ditemukan. Silakan periksa kembali nomor laporan Anda.
        </div>
        @endif

      </div>
    </div>
  </div>
</section>

<style>
.timeline {
  position: relative;
  padding-left: 30px;
}

.timeline-item {
  position: relative;
  padding-bottom: 20px;
}

.timeline-item::before {
  content: '';
  position: absolute;
  left: -24px;
  top: 8px;
  bottom: -12px;
  width: 2px;
  background: #e0e0e0;
}

.timeline-item:last-child::before {
  display: none;
}

.timeline-marker {
  position: absolute;
  left: -30px;
  top: 0;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: #e0e0e0;
  border: 2px solid #fff;
}

.timeline-item.completed .timeline-marker {
  background: #28a745;
}

.timeline-item.active .timeline-marker {
  background: #007bff;
}

.timeline-item.rejected .timeline-marker {
  background: #dc3545;
}

.timeline-item.pending .timeline-marker {
  background: #e0e0e0;
}
</style>
@endsection
