@extends('layouts.main')

@section('content')
<section id="breadcrumbs" class="breadcrumbs">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <h2>Registrasi Warga</h2>
      <ol>
        <li><a href="/">Beranda</a></li>
        <li>Registrasi</li>
      </ol>
    </div>
  </div>
</section>

<section class="inner-page pt-4 pb-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        
        <div class="card shadow">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-plus"></i> Form Registrasi Warga Desa Karangduren</h5>
          </div>
          <div class="card-body">
            
            <div class="alert alert-info">
              <i class="bi bi-info-circle"></i> <strong>Informasi:</strong> Silakan lengkapi data diri Anda untuk mendaftar. Data ini akan digunakan untuk pengajuan surat dan layanan online lainnya.
            </div>

            <form method="POST" action="{{ route('register') }}">
              @csrf
              <input type="hidden" name="role" value="warga">

              <h6 class="border-bottom pb-2 mb-3">Data Login</h6>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                  <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                         value="{{ old('name') }}" required autofocus>
                  @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Email <span class="text-danger">*</span></label>
                  <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                         value="{{ old('email') }}" required>
                  @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Password <span class="text-danger">*</span></label>
                  <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                  <small class="text-muted">Minimal 8 karakter</small>
                  @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                  <input type="password" name="password_confirmation" class="form-control" required>
                </div>
              </div>

              <h6 class="border-bottom pb-2 mb-3 mt-4">Data Pribadi</h6>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">NIK <span class="text-danger">*</span></label>
                  <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" 
                         value="{{ old('nik') }}" maxlength="16" required>
                  <small class="text-muted">16 digit sesuai KTP</small>
                  @error('nik')
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
                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                  </select>
                  @error('jenis_kelamin')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">RT/RW <span class="text-danger">*</span></label>
                  <input type="text" name="rt_rw" class="form-control @error('rt_rw') is-invalid @enderror" 
                         value="{{ old('rt_rw') }}" placeholder="001/002" required>
                  @error('rt_rw')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-12 mb-3">
                  <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                  <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror" required>{{ old('alamat') }}</textarea>
                  @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="agree" required>
                <label class="form-check-label" for="agree">
                  Saya menyetujui bahwa data yang saya berikan adalah benar dan dapat dipertanggungjawabkan
                </label>
              </div>

              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                  <i class="bi bi-check-circle"></i> Daftar Sekarang
                </button>
              </div>

              <div class="text-center mt-3">
                <p class="mb-0">Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
              </div>

            </form>

          </div>
        </div>

      </div>
    </div>
  </div>
</section>
@endsection
