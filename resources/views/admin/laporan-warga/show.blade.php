@extends('admin.layouts.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-primary">
                <div class="row align-items-center">
                    <div class="col-6">
                        <h5 class="card-title fw-semibold text-white">Detail Laporan Warga</h5>
                    </div>
                    <div class="col-6 text-right">
                        <a href="{{ route('admin.laporan-warga.index') }}" class="btn btn-warning float-end">
                            <i class="ti ti-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                @if (session()->has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="row">
                    <!-- Info Laporan -->
                    <div class="col-md-8">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge" style="background-color: {{ $laporan->kategoriLaporan->warna }}; font-size: 1rem; padding: 8px 15px;">
                                {{ $laporan->kategoriLaporan->nama_kategori }}
                            </span>
                            {!! $laporan->status_badge !!}
                        </div>

                        <h4 class="mb-3">Laporan #{{ $laporan->nomor_laporan }}</h4>

                        <table class="table table-bordered mb-4">
                            <tr>
                                <td width="30%"><strong>Nomor Laporan</strong></td>
                                <td>{{ $laporan->nomor_laporan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Pelapor</strong></td>
                                <td>{{ $laporan->nama_pelapor }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. Telepon</strong></td>
                                <td>{{ $laporan->no_telepon }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Laporan</strong></td>
                                <td>{{ $laporan->created_at->format('d F Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jumlah Views</strong></td>
                                <td>{{ $laporan->views }} views</td>
                            </tr>
                        </table>

                        <h5 class="mb-3">Isi Laporan</h5>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p style="white-space: pre-line;">{{ $laporan->isi_laporan }}</p>
                            </div>
                        </div>

                        @if($laporan->foto_bukti && count($laporan->foto_bukti) > 0)
                        <h5 class="mb-3 mt-4">Foto Bukti</h5>
                        <div class="row">
                            @foreach($laporan->foto_bukti as $foto)
                            <div class="col-md-4 mb-3">
                                <a href="{{ asset('storage/' . $foto) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $foto) }}" class="img-fluid img-thumbnail">
                                </a>
                            </div>
                            @endforeach
                        </div>
                        @endif


                    </div>

                    <!-- Update Status & Tanggapan -->
                    <div class="col-md-4">


                        @if($laporan->latitude && $laporan->longitude)
                        <div class="card mt-3">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="ti ti-map-pin"></i> Lokasi</h6>
                            </div>
                            <div class="card-body p-0">
                                <iframe 
                                    width="100%" 
                                    height="250" 
                                    frameborder="0" 
                                    scrolling="no"
                                    src="https://www.openstreetmap.org/export/embed.html?bbox={{ $laporan->longitude-0.01 }}%2C{{ $laporan->latitude-0.01 }}%2C{{ $laporan->longitude+0.01 }}%2C{{ $laporan->latitude+0.01 }}&layer=mapnik&marker={{ $laporan->latitude }}%2C{{ $laporan->longitude }}"
                                    style="border: 0">
                                </iframe>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
