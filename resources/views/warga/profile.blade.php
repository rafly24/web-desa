@extends('layouts.main')

@section('content')
<section id="breadcrumbs" class="breadcrumbs">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <h2>Profil Saya</h2>
      <ol>
        <li><a href="/">Beranda</a></li>
        <li><a href="{{ route('warga.dashboard') }}">Dashboard</a></li>
        <li>Profil</li>
      </ol>
    </div>
  </div>
</section>

<section class="inner-page pt-4">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle"></i> {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="card shadow">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-circle"></i> Edit Profil</h5>
          </div>
          <div class="card-body">
            
            <form action="{{ route('warga.profile.update') }}" method="POST">
              @csrf
              @method('PUT')

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                  <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                         value="{{ old('name', $user->name) }}" required>
                  @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Email <span class="text-danger">*</span></label>
                  <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                         value="{{ old('email', $user->email) }}" required>
                  @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">NIK <span class="text-danger">*</span></label>
                  <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" 
                         value="{{ old('nik', $user->nik) }}" maxlength="16" required>
                  @error('nik')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                  <input type="text" name="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" 
                         value="{{ old('no_telepon', $user->no_telepon) }}" required>
                  @error('no_telepon')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                  <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                         value="{{ old('tempat_lahir', $user->tempat_lahir) }}" required>
                  @error('tempat_lahir')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                  <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                         value="{{ old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}" required>
                  @error('tanggal_lahir')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                  <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                    <option value="">Pilih</option>
                    <option value="Laki-laki" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                  </select>
                  @error('jenis_kelamin')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">RT/RW <span class="text-danger">*</span></label>
                  <input type="text" name="rt_rw" class="form-control @error('rt_rw') is-invalid @enderror" 
                         value="{{ old('rt_rw', $user->rt_rw) }}" placeholder="001/002" required>
                  @error('rt_rw')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-12 mb-3">
                  <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                  <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror" required>{{ old('alamat', $user->alamat) }}</textarea>
                  @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-12">
                  <hr>
                  <h6>Ubah Password (Opsional)</h6>
                  <p class="small text-muted">Kosongkan jika tidak ingin mengubah password</p>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Password Baru</label>
                  <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                  @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Konfirmasi Password</label>
                  <input type="password" name="password_confirmation" class="form-control">
                </div>
              </div>

              <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                <a href="{{ route('warga.dashboard') }}" class="btn btn-secondary">
                  <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-save"></i> Simpan Perubahan
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
