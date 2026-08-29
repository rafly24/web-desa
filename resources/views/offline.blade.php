@extends('layouts.main')

@section('content')
<section class="inner-page pt-5 mt-5">
  <div class="container text-center pt-5 pb-5">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        
        <i class="bi bi-wifi-off text-danger" style="font-size: 5rem;"></i>
        <h2 class="mt-4 fw-bold">Anda Sedang Offline</h2>
        <p class="text-muted lead">Koneksi internet Anda terputus. Beberapa halaman tidak dapat diakses saat luring.</p>
        
        <div class="alert alert-info text-start mt-4 shadow-sm border-0">
          <h5><i class="bi bi-info-circle"></i> Namun, Anda tetap bisa:</h5>
          <ul class="mb-0">
            <li>Mengisi formulir <a href="{{ route('laporan-warga.create') }}" class="alert-link">Laporan Warga</a></li>
            <li>Meminta <a href="{{ route('pengajuan-surat.index') }}" class="alert-link">Pengajuan Surat</a></li>
          </ul>
          <p class="mt-2 mb-0 small">Data akan tersimpan secara lokal dan OTOMATIS terkirim begitu internet kembali normal.</p>
        </div>

        <button onclick="window.location.reload()" class="btn btn-primary mt-4 rounded-pill px-4 py-2">
          <i class="bi bi-arrow-clockwise"></i> Coba Ulang
        </button>

      </div>
    </div>
  </div>
</section>
@endsection
