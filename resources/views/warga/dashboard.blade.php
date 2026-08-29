@extends('layouts.main')

@section('content')
<section id="breadcrumbs" class="breadcrumbs">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <h2>Dashboard Warga</h2>
      <ol>
        <li><a href="/">Beranda</a></li>
        <li>Dashboard</li>
      </ol>
    </div>
  </div>
</section>

<section class="inner-page pt-4">
  <div class="container">
    
    <!-- Mobile Notification Tips -->
    @include('partials.mobile-notification-tips')
    
    <!-- Welcome Card -->
    <div class="card mb-4 shadow">
      <div class="card-body bg-primary text-white">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h3><i class="bi bi-person-circle"></i> Selamat Datang, {{ $user->name }}!</h3>
            <p class="mb-0">NIK: {{ $user->nik ?? '-' }}</p>
          </div>
          <div class="col-md-4 text-end">
            <a href="{{ route('warga.profile') }}" class="btn btn-light">
              <i class="bi bi-pencil"></i> Edit Profil
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
      <div class="col-md-3 mb-3">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <h2 class="text-primary">{{ $stats['total'] }}</h2>
            <p class="text-muted mb-0">Total Pengajuan</p>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <h2 class="text-warning">{{ $stats['pending'] }}</h2>
            <p class="text-muted mb-0">Pending</p>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <h2 class="text-info">{{ $stats['diproses'] }}</h2>
            <p class="text-muted mb-0">Diproses</p>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <h2 class="text-success">{{ $stats['selesai'] }}</h2>
            <p class="text-muted mb-0">Selesai</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="card mb-4 shadow-sm">
      <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-lightning"></i> Aksi Cepat</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 mb-2">
            <a href="{{ route('pengajuan-surat.index') }}" class="btn btn-primary w-100">
              <i class="bi bi-file-earmark-plus"></i> Buat Pengajuan Surat Baru
            </a>
          </div>
          <div class="col-md-6 mb-2">
            <a href="{{ route('laporan-warga.create') }}" class="btn btn-warning w-100">
              <i class="bi bi-megaphone"></i> Buat Laporan Warga
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Riwayat Pengajuan -->
    <div class="card shadow">
      <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Pengajuan Surat</h5>
      </div>
      <div class="card-body">
        
        @if($pengajuanList->count() > 0)
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th>No. Pengajuan</th>
                <th>Jenis Surat</th>
                <th>Tanggal Ajukan</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($pengajuanList as $pengajuan)
              <tr>
                <td><strong>{{ $pengajuan->nomor_pengajuan }}</strong></td>
                <td>{{ $pengajuan->jenisSurat->nama_surat }}</td>
                <td>{{ $pengajuan->created_at->format('d M Y H:i') }}</td>
                <td>{!! $pengajuan->status_badge !!}</td>
                <td>
                  <a href="{{ route('warga.pengajuan.show', $pengajuan->id) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-eye"></i> Detail
                  </a>
                  
                  @if($pengajuan->status == 'selesai' && $pengajuan->file_surat_jadi)
                  <a href="{{ Storage::url($pengajuan->file_surat_jadi) }}" target="_blank" class="btn btn-sm btn-success">
                    <i class="bi bi-download"></i> Unduh
                  </a>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
          {{ $pengajuanList->links() }}
        </div>
        
        @else
        <div class="text-center py-4">
          <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
          <p class="text-muted mt-2">Belum ada pengajuan surat</p>
          <a href="{{ route('pengajuan-surat.index') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Buat Pengajuan Pertama
          </a>
        </div>
        @endif

      </div>
    </div>

  </div>
</section>
@endsection
