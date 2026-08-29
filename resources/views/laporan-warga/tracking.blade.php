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



            @if($laporan->tanggal_ditanggapi)
            <div class="row mb-2">
              <div class="col-md-4"><strong>Tanggal Ditanggapi:</strong></div>
              <div class="col-md-8">{{ $laporan->tanggal_ditanggapi->format('d F Y H:i') }}</div>
            </div>
            @endif







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
