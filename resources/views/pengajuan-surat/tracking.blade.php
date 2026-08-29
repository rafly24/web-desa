@extends('layouts.main')

@section('content')
<section id="breadcrumbs" class="breadcrumbs">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <h2>Tracking Pengajuan Surat</h2>
      <ol>
        <li><a href="/">Beranda</a></li>
        <li><a href="{{ route('pengajuan-surat.index') }}">Pengajuan Surat</a></li>
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
            <h5 class="card-title mb-3"><i class="bi bi-search"></i> Cek Status Pengajuan</h5>
            <form action="{{ route('pengajuan-surat.cek-status') }}" method="POST">
              @csrf
              <div class="input-group">
                <input type="text" name="nomor_pengajuan" class="form-control" 
                       placeholder="Masukkan nomor pengajuan (contoh: PGJ-20241215-0001)" 
                       value="{{ request('nomor') }}" aria-label="Nomor Pengajuan Surat" required>
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-search"></i> Cek Status
                </button>
              </div>
            </form>
          </div>
        </div>

        @if($pengajuan)
        <!-- Result Box -->
        <div class="card shadow">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-file-earmark-check"></i> Detail Pengajuan</h5>
          </div>
          <div class="card-body">
            
            <div class="row mb-3">
              <div class="col-md-6">
                <strong>Nomor Pengajuan:</strong><br>
                <span class="text-primary fs-5">{{ $pengajuan->nomor_pengajuan }}</span>
              </div>
              <div class="col-md-6 text-md-end">
                <strong>Status:</strong><br>
                {!! $pengajuan->status_badge !!}
              </div>
            </div>

            <hr>

            <div class="row mb-2">
              <div class="col-md-4"><strong>Jenis Surat:</strong></div>
              <div class="col-md-8">{{ $pengajuan->jenisSurat->nama_surat }}</div>
            </div>

            <div class="row mb-2">
              <div class="col-md-4"><strong>Nama Pemohon:</strong></div>
              <div class="col-md-8">{{ $pengajuan->nama_lengkap }}</div>
            </div>

            <div class="row mb-2">
              <div class="col-md-4"><strong>NIK:</strong></div>
              <div class="col-md-8">{{ $pengajuan->nik }}</div>
            </div>

            <div class="row mb-2">
              <div class="col-md-4"><strong>Tanggal Pengajuan:</strong></div>
              <div class="col-md-8">{{ $pengajuan->created_at->format('d F Y H:i') }}</div>
            </div>

            @if($pengajuan->tanggal_diproses)
            <div class="row mb-2">
              <div class="col-md-4"><strong>Tanggal Diproses:</strong></div>
              <div class="col-md-8">{{ $pengajuan->tanggal_diproses->format('d F Y H:i') }}</div>
            </div>
            @endif

            @if($pengajuan->tanggal_selesai)
            <div class="row mb-2">
              <div class="col-md-4"><strong>Tanggal Selesai:</strong></div>
              <div class="col-md-8">{{ $pengajuan->tanggal_selesai->format('d F Y H:i') }}</div>
            </div>
            @endif



            @if($pengajuan->status === 'selesai' && $pengajuan->file_surat_jadi)
            <hr>
            <div class="text-center">
              <a href="{{ asset('storage/' . $pengajuan->file_surat_jadi) }}" 
                 class="btn btn-success btn-lg" target="_blank">
                <i class="bi bi-download"></i> Download Surat
              </a>
            </div>
            @endif

            <!-- Timeline -->
            <hr class="mt-4">
            <h6 class="mb-3">Timeline Proses</h6>
            <div class="timeline">
              <div class="timeline-item {{ $pengajuan->status !== 'pending' ? 'completed' : 'active' }}">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                  <h6>Pengajuan Diterima</h6>
                  <p class="text-muted small mb-0">{{ $pengajuan->created_at->format('d M Y H:i') }}</p>
                </div>
              </div>

              <div class="timeline-item {{ in_array($pengajuan->status, ['diproses', 'selesai']) ? 'completed' : ($pengajuan->status === 'pending' ? 'pending' : 'active') }}">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                  <h6>Sedang Diproses</h6>
                  @if($pengajuan->tanggal_diproses)
                    <p class="text-muted small mb-0">{{ $pengajuan->tanggal_diproses->format('d M Y H:i') }}</p>
                  @endif
                </div>
              </div>

              <div class="timeline-item {{ $pengajuan->status === 'selesai' ? 'completed' : ($pengajuan->status === 'ditolak' ? 'rejected' : 'pending') }}">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                  <h6>{{ $pengajuan->status === 'ditolak' ? 'Ditolak' : 'Selesai' }}</h6>
                  @if($pengajuan->tanggal_selesai)
                    <p class="text-muted small mb-0">{{ $pengajuan->tanggal_selesai->format('d M Y H:i') }}</p>
                  @endif
                </div>
              </div>
            </div>

          </div>
        </div>

        @elseif(request('nomor'))
        <div class="alert alert-warning text-center">
          <i class="bi bi-exclamation-triangle"></i> Nomor pengajuan tidak ditemukan. Silakan periksa kembali nomor pengajuan Anda.
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
