@extends('layouts.main')

@section('content')
<section id="breadcrumbs" class="breadcrumbs">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <h2>Buat Laporan Baru</h2>
      <ol>
        <li><a href="/">Beranda</a></li>
        <li><a href="{{ route('laporan-warga.index') }}">Laporan Warga</a></li>
        <li>Buat Laporan</li>
      </ol>
    </div>
  </div>
</section>

<section class="inner-page pt-4">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        
        <div class="card shadow">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-megaphone"></i> Form Laporan Warga</h5>
          </div>
          <div class="card-body">
            
            <div class="alert alert-info">
              <h6><i class="bi bi-info-circle"></i> Informasi Penting</h6>
              <ul class="mb-0 small">
                <li>Sampaikan laporan dengan jelas dan objektif</li>
                <li>Upload foto bukti untuk mempercepat proses tindak lanjut</li>
                <li>Laporan akan ditanggapi maksimal 3 hari kerja</li>
                <li>Centang "Laporan Anonim" jika tidak ingin identitas ditampilkan</li>
              </ul>
            </div>

            <form id="formLaporan" action="{{ route('laporan-warga.store') }}" method="POST" enctype="multipart/form-data">
              @csrf

              <h6 class="border-bottom pb-2 mb-3">Data Pelapor</h6>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                  <input type="text" name="nama_pelapor" class="form-control @error('nama_pelapor') is-invalid @enderror" 
                         value="{{ old('nama_pelapor') }}" required>
                  @error('nama_pelapor')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">No. Telepon/WA <span class="text-danger">*</span></label>
                  <input type="text" name="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" 
                         value="{{ old('no_telepon') }}" required>
                  @error('no_telepon')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <h6 class="border-bottom pb-2 mb-3 mt-4">Detail Pengaduan</h6>

              <div class="row">
                <div class="col-md-12 mb-3">
                  <label class="form-label">Kategori Pengaduan <span class="text-danger">*</span></label>
                  <select name="kategori_laporan_id" class="form-select @error('kategori_laporan_id') is-invalid @enderror" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($kategori as $kat)
                    <option value="{{ $kat->id }}" {{ old('kategori_laporan_id') == $kat->id ? 'selected' : '' }}>
                      {{ $kat->nama_kategori }}
                    </option>
                    @endforeach
                  </select>
                  @error('kategori_laporan_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-12 mb-3">
                  <label class="form-label">Isi Pengaduan <span class="text-danger">*</span></label>
                  <textarea name="isi_laporan" rows="5" class="form-control @error('isi_laporan') is-invalid @enderror" 
                            placeholder="Jelaskan detail permasalahan atau keluhan Anda" required>{{ old('isi_laporan') }}</textarea>
                  @error('isi_laporan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-12 mb-3">
                  <label class="form-label">Lampiran (Foto Bukti)</label>
                  <input type="file" name="foto_bukti[]" class="form-control @error('foto_bukti.*') is-invalid @enderror" 
                         accept="image/*" multiple>
                  <small class="text-muted">Bisa upload lebih dari 1 foto. Max 2MB per foto (JPG, PNG)</small>
                  @error('foto_bukti.*')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="{{ route('laporan-warga.index') }}" class="btn btn-secondary">
                  <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-send"></i> Kirim Laporan
                </button>
              </div>

            </form>

          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function() {
      if(typeof initAutosave === 'function') {
          initAutosave('formLaporan', 'draft_laporan');
          handleFormSubmitOffline('formLaporan', 'draft_laporan', "{{ route('laporan-warga.store') }}", "{{ route('laporan-warga.index') }}", 'sync-laporan');
      }
  });
</script>
@endsection
