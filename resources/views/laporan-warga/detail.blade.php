@extends('layouts.main')

@section('content')
<section id="breadcrumbs" class="breadcrumbs">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <h2>Detail Laporan</h2>
      <ol>
        <li><a href="/">Beranda</a></li>
        <li><a href="{{ route('laporan-warga.index') }}">Laporan Warga</a></li>
        <li>Detail</li>
      </ol>
    </div>
  </div>
</section>

<section class="inner-page pt-4">
  <div class="container">
    <div class="row">
      
      <!-- Main Content -->
      <div class="col-lg-8 mb-4">
        <div class="card shadow">
          <div class="card-body">
            
            <div class="d-flex justify-content-between align-items-start mb-3">
              <span class="badge" style="background-color: {{ $laporan->kategoriLaporan->warna }}; font-size: 0.9rem;">
                <i class="bi bi-tag"></i> {{ $laporan->kategoriLaporan->nama_kategori }}
              </span>
              {!! $laporan->status_badge !!}
            </div>

            <h3 class="mb-3">{{ $laporan->judul_laporan }}</h3>

            <div class="row text-muted small mb-4">
              <div class="col-md-6 mb-2">
                <i class="bi bi-person"></i> {{ $laporan->is_anonim ? 'Anonim' : $laporan->nama_pelapor }}
              </div>
              <div class="col-md-6 mb-2">
                <i class="bi bi-calendar"></i> {{ $laporan->created_at->format('d F Y H:i') }}
              </div>
              <div class="col-md-6 mb-2">
                <i class="bi bi-geo-alt"></i> {{ $laporan->lokasi_kejadian }}
              </div>
              <div class="col-md-6 mb-2">
                <i class="bi bi-eye"></i> {{ $laporan->views }} views
              </div>
            </div>

            {!! $laporan->prioritas_badge !!}

            <hr>

            <h5 class="mb-3">Deskripsi Laporan</h5>
            <p style="white-space: pre-line;">{{ $laporan->isi_laporan }}</p>

            @if($laporan->foto_bukti && count($laporan->foto_bukti) > 0)
            <hr>
            <h5 class="mb-3">Foto Bukti</h5>
            <div class="row">
              @foreach($laporan->foto_bukti as $foto)
              <div class="col-md-4 mb-3">
                <a href="{{ asset('storage/' . $foto) }}" target="_blank">
                  <img src="{{ asset('storage/' . $foto) }}" class="img-fluid rounded shadow-sm" alt="Foto Bukti">
                </a>
              </div>
              @endforeach
            </div>
            @endif

            @if($laporan->tanggapan_admin)
            <hr>
            <div class="alert alert-success">
              <h5><i class="bi bi-check-circle"></i> Tanggapan Admin</h5>
              <p class="mb-0" style="white-space: pre-line;">{{ $laporan->tanggapan_admin }}</p>
              
              @if($laporan->tanggal_ditanggapi)
              <small class="text-muted d-block mt-2">
                <i class="bi bi-calendar"></i> {{ $laporan->tanggal_ditanggapi->format('d F Y H:i') }}
              </small>
              @endif

              @if($laporan->admin)
              <small class="text-muted">
                <i class="bi bi-person"></i> Ditangani oleh: {{ $laporan->admin->name }}
              </small>
              @endif
            </div>

            @if($laporan->foto_tindak_lanjut)
            <h6>Foto Tindak Lanjut</h6>
            <a href="{{ asset('storage/' . $laporan->foto_tindak_lanjut) }}" target="_blank">
              <img src="{{ asset('storage/' . $laporan->foto_tindak_lanjut) }}" class="img-fluid rounded shadow-sm" alt="Foto Tindak Lanjut" style="max-height: 300px;">
            </a>
            @endif
            @endif

          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="col-lg-4">
        
        <!-- Info Box -->
        <div class="card shadow mb-4">
          <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="bi bi-info-circle"></i> Informasi Laporan</h6>
          </div>
          <div class="card-body">
            <table class="table table-sm">
              <tr>
                <td><strong>Nomor Laporan</strong></td>
                <td>{{ $laporan->nomor_laporan }}</td>
              </tr>
              <tr>
                <td><strong>Status</strong></td>
                <td>{!! $laporan->status_badge !!}</td>
              </tr>
              <tr>
                <td><strong>Prioritas</strong></td>
                <td>{!! $laporan->prioritas_badge !!}</td>
              </tr>
              <tr>
                <td><strong>Tanggal Kejadian</strong></td>
                <td>{{ $laporan->tanggal_kejadian->format('d F Y') }}</td>
              </tr>
              @if($laporan->tanggal_selesai)
              <tr>
                <td><strong>Tanggal Selesai</strong></td>
                <td>{{ $laporan->tanggal_selesai->format('d F Y') }}</td>
              </tr>
              @endif
            </table>
          </div>
        </div>

        <!-- Map (if coordinates available) -->
        @if($laporan->latitude && $laporan->longitude)
        <div class="card shadow">
          <div class="card-header bg-primary text-white">
            <h6 class="mb-0"><i class="bi bi-geo-alt"></i> Lokasi</h6>
          </div>
          <div class="card-body p-0">
            <iframe 
              width="100%" 
              height="250" 
              frameborder="0" 
              scrolling="no" 
              marginheight="0" 
              marginwidth="0" 
              src="https://www.openstreetmap.org/export/embed.html?bbox={{ $laporan->longitude-0.01 }}%2C{{ $laporan->latitude-0.01 }}%2C{{ $laporan->longitude+0.01 }}%2C{{ $laporan->latitude+0.01 }}&layer=mapnik&marker={{ $laporan->latitude }}%2C{{ $laporan->longitude }}"
              style="border: 0">
            </iframe>
          </div>
        </div>
        @endif

      </div>

    </div>
  </div>
</section>
@endsection
