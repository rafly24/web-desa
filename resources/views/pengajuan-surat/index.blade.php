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



  </div>
</section>
@endsection
