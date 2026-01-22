@extends('layouts.main')

@section('content')
<section id="breadcrumbs" class="breadcrumbs">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <h2>Detail Pengajuan</h2>
      <ol>
        <li><a href="/">Beranda</a></li>
        <li><a href="{{ route('warga.dashboard') }}">Dashboard</a></li>
        <li>Detail Pengajuan</li>
      </ol>
    </div>
  </div>
</section>

<section class="inner-page pt-4">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        
        <!-- Detail Pengajuan -->
        <div class="card shadow mb-4">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Informasi Pengajuan</h5>
          </div>
          <div class="card-body">
            <table class="table table-borderless">
              <tr>
                <th width="200">No. Pengajuan</th>
                <td><strong class="text-primary">{{ $pengajuan->nomor_pengajuan }}</strong></td>
              </tr>
              <tr>
                <th>Jenis Surat</th>
                <td>{{ $pengajuan->jenisSurat->nama_surat }}</td>
              </tr>
              <tr>
                <th>Tanggal Pengajuan</th>
                <td>{{ $pengajuan->created_at->format('d F Y, H:i') }} WIB</td>
              </tr>
              <tr>
                <th>Status</th>
                <td>{!! $pengajuan->status_badge !!}</td>
              </tr>
              <tr>
                <th>Keperluan</th>
                <td>{{ $pengajuan->keperluan }}</td>
              </tr>
              
              @if($pengajuan->tanggal_diproses)
              <tr>
                <th>Tanggal Diproses</th>
                <td>{{ $pengajuan->tanggal_diproses->format('d F Y, H:i') }} WIB</td>
              </tr>
              @endif

              @if($pengajuan->tanggal_selesai)
              <tr>
                <th>Tanggal Selesai</th>
                <td>{{ $pengajuan->tanggal_selesai->format('d F Y, H:i') }} WIB</td>
              </tr>
              @endif

              @if($pengajuan->admin)
              <tr>
                <th>Diproses Oleh</th>
                <td>{{ $pengajuan->admin->name }}</td>
              </tr>
              @endif

              @if($pengajuan->catatan_admin)
              <tr>
                <th>Catatan Admin</th>
                <td>
                  <div class="alert alert-info mb-0">
                    {{ $pengajuan->catatan_admin }}
                  </div>
                </td>
              </tr>
              @endif
            </table>
          </div>
        </div>

        <!-- Berkas Upload -->
        <div class="card shadow mb-4">
          <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-paperclip"></i> Berkas yang Diupload</h5>
          </div>
          <div class="card-body">
            <div class="row">
              @if($pengajuan->file_ktp)
              <div class="col-md-4 mb-3">
                <label class="form-label small text-muted">KTP</label>
                <div>
                  <a href="{{ Storage::url($pengajuan->file_ktp) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i> Lihat KTP
                  </a>
                </div>
              </div>
              @endif

              @if($pengajuan->file_kk)
              <div class="col-md-4 mb-3">
                <label class="form-label small text-muted">Kartu Keluarga</label>
                <div>
                  <a href="{{ Storage::url($pengajuan->file_kk) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i> Lihat KK
                  </a>
                </div>
              </div>
              @endif

              @if($pengajuan->file_pendukung)
              <div class="col-md-4 mb-3">
                <label class="form-label small text-muted">Berkas Pendukung</label>
                <div>
                  <a href="{{ Storage::url($pengajuan->file_pendukung) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i> Lihat File
                  </a>
                </div>
              </div>
              @endif
            </div>
          </div>
        </div>

        <!-- Download Surat Jadi -->
        @if($pengajuan->status == 'selesai' && $pengajuan->file_surat_jadi)
        <div class="card shadow mb-4 border-success">
          <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-check-circle"></i> Surat Anda Sudah Jadi!</h5>
          </div>
          <div class="card-body text-center">
            <i class="bi bi-file-earmark-pdf text-success" style="font-size: 4rem;"></i>
            <p class="mt-3">Surat Anda telah selesai diproses. Silakan unduh file di bawah ini:</p>
            <a href="{{ Storage::url($pengajuan->file_surat_jadi) }}" target="_blank" class="btn btn-success btn-lg">
              <i class="bi bi-download"></i> Unduh Surat (PDF)
            </a>
          </div>
        </div>
        @endif

      </div>

      <!-- Sidebar -->
      <div class="col-lg-4">
        
        <!-- Status Timeline -->
        <div class="card shadow mb-4">
          <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-diagram-3"></i> Timeline Status</h5>
          </div>
          <div class="card-body">
            <ul class="list-unstyled timeline">
              <li class="mb-3">
                <div class="d-flex align-items-start">
                  <div class="flex-shrink-0">
                    <i class="bi bi-check-circle-fill text-success"></i>
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <strong>Pengajuan Dibuat</strong>
                    <p class="small text-muted mb-0">{{ $pengajuan->created_at->format('d M Y, H:i') }}</p>
                  </div>
                </div>
              </li>

              <li class="mb-3">
                <div class="d-flex align-items-start">
                  <div class="flex-shrink-0">
                    <i class="bi bi-{{ in_array($pengajuan->status, ['diproses', 'selesai']) ? 'check-circle-fill text-success' : 'circle text-muted' }}"></i>
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <strong>Sedang Diproses</strong>
                    @if($pengajuan->tanggal_diproses)
                    <p class="small text-muted mb-0">{{ $pengajuan->tanggal_diproses->format('d M Y, H:i') }}</p>
                    @else
                    <p class="small text-muted mb-0">Menunggu...</p>
                    @endif
                  </div>
                </div>
              </li>

              <li class="mb-3">
                <div class="d-flex align-items-start">
                  <div class="flex-shrink-0">
                    <i class="bi bi-{{ $pengajuan->status == 'selesai' ? 'check-circle-fill text-success' : 'circle text-muted' }}"></i>
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <strong>Selesai</strong>
                    @if($pengajuan->tanggal_selesai)
                    <p class="small text-muted mb-0">{{ $pengajuan->tanggal_selesai->format('d M Y, H:i') }}</p>
                    @else
                    <p class="small text-muted mb-0">Menunggu...</p>
                    @endif
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>

        <!-- Info Tambahan -->
        <div class="card shadow">
          <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informasi</h5>
          </div>
          <div class="card-body">
            <p class="small text-muted mb-3">
              <i class="bi bi-clock"></i> Estimasi waktu proses: <strong>2-3 hari kerja</strong>
            </p>
            <p class="small text-muted mb-3">
              <i class="bi bi-telephone"></i> Hubungi kami jika ada kendala di nomor: <strong>0812-xxxx-xxxx</strong>
            </p>
            <a href="{{ route('warga.dashboard') }}" class="btn btn-secondary btn-sm w-100">
              <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
@endsection
