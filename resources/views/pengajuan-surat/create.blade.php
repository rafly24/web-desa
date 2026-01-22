@extends('layouts.main')

@section('content')
<section id="breadcrumbs" class="breadcrumbs">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <h2>Form Pengajuan Surat</h2>
      <ol>
        <li><a href="/">Beranda</a></li>
        <li><a href="{{ route('pengajuan-surat.index') }}">Pengajuan Surat</a></li>
        <li>Form</li>
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
            <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> {{ $jenisSurat->nama_surat }}</h5>
          </div>
          <div class="card-body">
            
            @if($jenisSurat->persyaratan)
            <div class="alert alert-info">
              <strong>Persyaratan:</strong><br>
              {!! nl2br($jenisSurat->persyaratan) !!}
            </div>
            @endif

            <form action="{{ route('pengajuan-surat.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="jenis_surat_id" value="{{ $jenisSurat->id }}">

              <div class="alert alert-success">
                <i class="bi bi-info-circle"></i> <strong>Data Anda otomatis diambil dari profil.</strong> 
                Jika ada yang kurang lengkap, silakan <a href="{{ route('warga.profile') }}" class="alert-link">lengkapi profil Anda</a> terlebih dahulu.
              </div>

              <h6 class="border-bottom pb-2 mb-3">Data Pemohon (Otomatis dari Profil)</h6>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">NIK</label>
                  <input type="text" class="form-control" value="{{ $user->nik ?? '-' }}" readonly>
                  <small class="text-muted">Data diambil dari profil Anda</small>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Nama Lengkap</label>
                  <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                  <small class="text-muted">Data diambil dari profil Anda</small>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Tempat, Tanggal Lahir</label>
                  <input type="text" class="form-control" 
                         value="{{ ($user->tempat_lahir ?? '-') . ', ' . ($user->tanggal_lahir ? $user->tanggal_lahir->format('d F Y') : '-') }}" readonly>
                  <small class="text-muted">Data diambil dari profil Anda</small>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Jenis Kelamin</label>
                  <input type="text" class="form-control" value="{{ $user->jenis_kelamin ?? '-' }}" readonly>
                  <small class="text-muted">Data diambil dari profil Anda</small>
                </div>

                <div class="col-md-12 mb-3">
                  <label class="form-label">Alamat</label>
                  <textarea class="form-control" rows="2" readonly>{{ $user->alamat ?? '-' }}</textarea>
                  <small class="text-muted">RT/RW: {{ $user->rt_rw ?? '-' }}</small>
                </div>
              </div>

              <h6 class="border-bottom pb-2 mb-3 mt-4">Keperluan Surat</h6>

              <div class="row">"
                <div class="col-md-6 mb-3">
                  <label class="form-label">NIK <span class="text-danger">*</span></label>
                  <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" 
                         value="{{ old('nik') }}" maxlength="16" required>
                  @error('nik')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                  <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                         value="{{ old('nama_lengkap') }}" required>
                  @error('nama_lengkap')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                  <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                         value="{{ old('tempat_lahir') }}" required>
                  @error('tempat_lahir')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                  <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                         value="{{ old('tanggal_lahir') }}" required>
                  @error('tanggal_lahir')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                  <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                    <option value="">Pilih</option>
                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                  </select>
                  @error('jenis_kelamin')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                  <input type="text" name="pekerjaan" class="form-control @error('pekerjaan') is-invalid @enderror" 
                         value="{{ old('pekerjaan') }}" required>
                  @error('pekerjaan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-12 mb-3">
                  <label class="form-label">Alamat <span class="text-danger">*</span></label>
                  <textarea name="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror" required>{{ old('alamat') }}</textarea>
                  @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-3 mb-3">
                  <label class="form-label">RT/RW <span class="text-danger">*</span></label>
                  <input type="text" name="rt_rw" class="form-control @error('rt_rw') is-invalid @enderror" 
                         value="{{ old('rt_rw') }}" placeholder="001/002" required>
                  @error('rt_rw')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-3 mb-3">
                  <label class="form-label">Desa/Kelurahan <span class="text-danger">*</span></label>
                  <input type="text" name="desa_kelurahan" class="form-control @error('desa_kelurahan') is-invalid @enderror" 
                         value="{{ old('desa_kelurahan', 'Karangduren') }}" required>
                  @error('desa_kelurahan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-3 mb-3">
                  <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                  <input type="text" name="kecamatan" class="form-control @error('kecamatan') is-invalid @enderror" 
                         value="{{ old('kecamatan') }}" required>
                  @error('kecamatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-3 mb-3">
                  <label class="form-label">Kabupaten <span class="text-danger">*</span></label>
                  <input type="text" name="kabupaten" class="form-control @error('kabupaten') is-invalid @enderror" 
                         value="{{ old('kabupaten') }}" required>
                  @error('kabupaten')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">No. Telepon/HP <span class="text-danger">*</span></label>
                  <input type="text" name="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" 
                         value="{{ old('no_telepon') }}" required>
                  @error('no_telepon')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-12 mb-3">
                  <label class="form-label">Keperluan <span class="text-danger">*</span></label>
                  <textarea name="keperluan" rows="3" class="form-control @error('keperluan') is-invalid @enderror" 
                            placeholder="Jelaskan untuk keperluan apa surat ini dibutuhkan" required>{{ old('keperluan') }}</textarea>
                  @error('keperluan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <h6 class="border-bottom pb-2 mb-3 mt-4">Upload Dokumen</h6>

              <div class="row">
                <div class="col-md-4 mb-3">
                  <label class="form-label">Foto KTP <span class="text-danger">*</span></label>
                  <input type="file" name="file_ktp" class="form-control @error('file_ktp') is-invalid @enderror" 
                         accept="image/*" required>
                  <small class="text-muted">Max 2MB (JPG, PNG)</small>
                  @error('file_ktp')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-4 mb-3">
                  <label class="form-label">Foto KK</label>
                  <input type="file" name="file_kk" class="form-control @error('file_kk') is-invalid @enderror" 
                         accept="image/*">
                  <small class="text-muted">Max 2MB (JPG, PNG)</small>
                  @error('file_kk')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-4 mb-3">
                  <label class="form-label">Dokumen Pendukung</label>
                  <input type="file" name="file_pendukung" class="form-control @error('file_pendukung') is-invalid @enderror" 
                         accept="image/*,application/pdf">
                  <small class="text-muted">Max 2MB (JPG, PNG, PDF)</small>
                  @error('file_pendukung')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="{{ route('pengajuan-surat.index') }}" class="btn btn-secondary">
                  <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-send"></i> Kirim Pengajuan
                </button>
              </div>

            </form>

          </div>
        </div>

      </div>
    </div>

  </div>
</section>
@endsection
