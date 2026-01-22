@extends('layouts.main')

@section('content')
<section id="breadcrumbs" class="breadcrumbs">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <h2>Pengajuan Surat Online</h2>
      <ol>
        <li><a href="/">Beranda</a></li>
        <li>Pengajuan Surat</li>
      </ol>
    </div>
  </div>
</section>

<section class="inner-page pt-4">
  <div class="container">
    
    <!-- Tracking Box -->
    <div class="card mb-4 shadow-sm">
      <div class="card-body bg-light">
        <h5 class="card-title mb-3"><i class="bi bi-search"></i> Cek Status Pengajuan</h5>
        <form action="{{ route('pengajuan-surat.cek-status') }}" method="POST" class="row g-3">
          @csrf
          <div class="col-md-9">
            <input type="text" name="nomor_pengajuan" class="form-control" placeholder="Masukkan nomor pengajuan (contoh: PGJ-20241215-0001)" required>
          </div>
          <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Cek Status</button>
          </div>
        </form>
      </div>
    </div>

    <div class="section-title">
      <h2>Pilih Jenis Surat</h2>
      <p>Silakan pilih jenis surat yang ingin Anda ajukan</p>
    </div>

    <div class="row">
      @forelse ($jenisSurat as $jenis)
      <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <div class="text-center mb-3">
              <i class="bi bi-file-earmark-text" style="font-size: 3rem; color: #0d6efd;"></i>
            </div>
            <h5 class="card-title text-center">{{ $jenis->nama_surat }}</h5>
            <p class="card-text text-muted small">
              {{ $jenis->keterangan ?? 'Pengajuan surat ' . $jenis->nama_surat }}
            </p>
            
            @if($jenis->persyaratan)
            <div class="mt-3">
              <strong class="text-sm">Persyaratan:</strong>
              <p class="small text-muted mb-0">{!! nl2br($jenis->persyaratan) !!}</p>
            </div>
            @endif
          </div>
          <div class="card-footer bg-transparent border-top-0">
            <a href="{{ route('pengajuan-surat.create', $jenis->kode_surat) }}" class="btn btn-primary w-100">
              <i class="bi bi-pencil-square"></i> Ajukan Surat
            </a>
          </div>
        </div>
      </div>
      @empty
      <div class="col-12">
        <div class="alert alert-info text-center">
          <i class="bi bi-info-circle"></i> Belum ada jenis surat yang tersedia saat ini.
        </div>
      </div>
      @endforelse
    </div>

    <!-- Info Box -->
    <div class="row mt-4">
      <div class="col-md-12">
        <div class="alert alert-warning">
          <h5><i class="bi bi-exclamation-triangle"></i> Informasi Penting</h5>
          <ul class="mb-0">
            <li>Pastikan data yang Anda masukkan sudah benar dan sesuai</li>
            <li>Upload file KTP dengan jelas dan mudah dibaca</li>
            <li>Proses verifikasi membutuhkan waktu maksimal 3 hari kerja</li>
            <li>Anda akan mendapatkan nomor pengajuan untuk tracking status</li>
          </ul>
        </div>
      </div>
    </div>

  </div>
</section>
@endsection
