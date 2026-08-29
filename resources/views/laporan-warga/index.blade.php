@extends('layouts.main')

@section('content')
<section id="breadcrumbs" class="breadcrumbs">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <h2>Laporan Warga</h2>
      <ol>
        <li><a href="/">Beranda</a></li>
        <li>Laporan Warga</li>
      </ol>
    </div>
  </div>
</section>

<section class="inner-page pt-4">
  <div class="container">
    
    <!-- Hero Section -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card bg-primary text-white">
          <div class="card-body p-4">
            <div class="row align-items-center">
              <div class="col-md-8">
                <h3><i class="bi bi-megaphone"></i> Sampaikan Aspirasi Anda</h3>
                <p class="mb-3">Laporkan permasalahan atau keluhan seputar pelayanan dan infrastruktur desa. Kami akan segera menindaklanjuti laporan Anda.</p>
                <a href="{{ route('laporan-warga.create') }}" class="btn btn-light btn-lg">
                  <i class="bi bi-plus-circle"></i> Buat Laporan Baru
                </a>
              </div>
              <div class="col-md-4 text-center">
                <i class="bi bi-chat-left-text" style="font-size: 6rem; opacity: 0.3;"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- Kategori Filter -->
    <div class="row mb-4">
      <div class="col-12">
        <h5 class="mb-3">Kategori Laporan</h5>
        <div class="d-flex flex-wrap gap-2">
          <a href="{{ route('laporan-warga.index') }}" 
             class="btn {{ !request('kategori') ? 'btn-primary' : 'btn-outline-primary' }}">
            <i class="bi bi-grid"></i> Semua
          </a>
          @foreach($kategori as $kat)
          <a href="{{ route('laporan-warga.index', ['kategori' => $kat->id]) }}" 
             class="btn {{ request('kategori') == $kat->id ? 'btn-primary' : 'btn-outline-primary' }}">
            @if($kat->icon)
              <i class="bi bi-{{ $kat->icon }}"></i>
            @endif
            {{ $kat->nama_kategori }}
          </a>
          @endforeach
        </div>
      </div>
    </div>

    <!-- Laporan List -->
    <div class="section-title">
      <h2>Daftar Laporan</h2>
      <p>Laporan dari warga yang dapat dilihat publik</p>
    </div>

    <div class="row">
      @forelse($laporanList as $laporan)
      <div class="col-lg-6 mb-4" data-aos="fade-up">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <span class="badge" style="background-color: {{ $laporan->kategoriLaporan->warna }}">
                {{ $laporan->kategoriLaporan->nama_kategori }}
              </span>
            </div>
            
            <h5 class="card-title">{{ $laporan->judul_laporan }}</h5>
            
            <p class="card-text text-muted small">
              {{ \Str::limit($laporan->isi_laporan, 150) }}
            </p>

            <div class="row text-muted small mb-3">
              <div class="col-6">
                <i class="bi bi-person"></i> {{ $laporan->is_anonim ? 'Anonim' : $laporan->nama_pelapor }}
              </div>
              <div class="col-6">
                <i class="bi bi-geo-alt"></i> {{ \Str::limit($laporan->lokasi_kejadian, 30) }}
              </div>
              <div class="col-6 mt-2">
                <i class="bi bi-calendar"></i> {{ $laporan->created_at->format('d M Y') }}
              </div>
              <div class="col-6 mt-2">
                <i class="bi bi-eye"></i> {{ $laporan->views }} views
              </div>
            </div>



          </div>
          <div class="card-footer bg-transparent border-top-0">
            <a href="{{ route('laporan-warga.detail', $laporan->id) }}" class="btn btn-sm btn-primary">
              <i class="bi bi-eye"></i> Lihat Detail
            </a>
          </div>
        </div>
      </div>
      @empty
      <div class="col-12">
        <div class="alert alert-info text-center">
          <i class="bi bi-info-circle"></i> Belum ada laporan yang tersedia saat ini.
        </div>
      </div>
      @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
      {{ $laporanList->links() }}
    </div>

  </div>
</section>
@endsection
