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
            
            <form id="formSurat" action="{{ route('pengajuan-surat.store') }}" method="POST" onsubmit="setTimeout(() => { this.reset(); localStorage.removeItem('draft_surat'); }, 1000);">
              @csrf
              <input type="hidden" name="jenis_surat_id" value="{{ $jenisSurat->id }}">

              <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Silakan lengkapi data diri Anda di bawah ini untuk keperluan pengajuan surat.
              </div>

              <h6 class="border-bottom pb-2 mb-3">Data Pemohon</h6>

              <div class="row">
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
                  <label class="form-label">Kebangsaan/Suku <span class="text-danger">*</span></label>
                  <input type="text" name="kebangsaan" class="form-control @error('kebangsaan') is-invalid @enderror" 
                         value="{{ old('kebangsaan') }}" placeholder="Contoh: WNI/Sunda" required>
                  @error('kebangsaan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Agama <span class="text-danger">*</span></label>
                  <select name="agama" class="form-select @error('agama') is-invalid @enderror" required>
                    <option value="">Pilih</option>
                    <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                    <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                    <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                    <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                    <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                    <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                    <option value="Lainnya" {{ old('agama') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                  </select>
                  @error('agama')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Status Perkawinan <span class="text-danger">*</span></label>
                  <select name="status_perkawinan" class="form-select @error('status_perkawinan') is-invalid @enderror" required>
                    <option value="">Pilih</option>
                    <option value="Belum Kawin" {{ old('status_perkawinan') == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
                    <option value="Kawin" {{ old('status_perkawinan') == 'Kawin' ? 'selected' : '' }}>Kawin</option>
                    <option value="Cerai Hidup" {{ old('status_perkawinan') == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                    <option value="Cerai Mati" {{ old('status_perkawinan') == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
                  </select>
                  @error('status_perkawinan')
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
                  <textarea name="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror" placeholder="Alamat sesuai KTP" required>{{ old('alamat') }}</textarea>
                  @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                @if($jenisSurat->kode_surat == 'SKTM')
                <div class="col-md-12 mt-4">
                  <h6 class="border-bottom pb-2 mb-3 text-primary">Data Anak / Tanggungan (SKTM)</h6>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">NIK Anak <span class="text-danger">*</span></label>
                  <input type="text" name="nik_anak" class="form-control @error('nik_anak') is-invalid @enderror" 
                         value="{{ old('nik_anak') }}" maxlength="16" required>
                  @error('nik_anak')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Nama Lengkap Anak <span class="text-danger">*</span></label>
                  <input type="text" name="nama_anak" class="form-control @error('nama_anak') is-invalid @enderror" 
                         value="{{ old('nama_anak') }}" required>
                  @error('nama_anak')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Tempat Lahir Anak <span class="text-danger">*</span></label>
                  <input type="text" name="tempat_lahir_anak" class="form-control @error('tempat_lahir_anak') is-invalid @enderror" 
                         value="{{ old('tempat_lahir_anak') }}" required>
                  @error('tempat_lahir_anak')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Tanggal Lahir Anak <span class="text-danger">*</span></label>
                  <input type="date" name="tanggal_lahir_anak" class="form-control @error('tanggal_lahir_anak') is-invalid @enderror" 
                         value="{{ old('tanggal_lahir_anak') }}" required>
                  @error('tanggal_lahir_anak')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Jenis Kelamin Anak <span class="text-danger">*</span></label>
                  <select name="jenis_kelamin_anak" class="form-select @error('jenis_kelamin_anak') is-invalid @enderror" required>
                    <option value="">Pilih</option>
                    <option value="L" {{ old('jenis_kelamin_anak') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin_anak') == 'P' ? 'selected' : '' }}>Perempuan</option>
                  </select>
                  @error('jenis_kelamin_anak')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Pekerjaan Anak <span class="text-danger">*</span></label>
                  <input type="text" name="pekerjaan_anak" class="form-control @error('pekerjaan_anak') is-invalid @enderror" 
                         value="{{ old('pekerjaan_anak') }}" required>
                  @error('pekerjaan_anak')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-12 mb-3">
                  <label class="form-label">Alamat Anak <span class="text-danger">*</span></label>
                  <textarea name="alamat_anak" rows="2" class="form-control @error('alamat_anak') is-invalid @enderror" placeholder="Isi alamat lengkap anak..." required>{{ old('alamat_anak') }}</textarea>
                  @error('alamat_anak')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                
                <div class="col-md-12 mt-4">
                  <h6 class="border-bottom pb-2 mb-3 text-primary">Keterangan Lanjutan</h6>
                </div>
                @endif

                @if($jenisSurat->kode_surat != 'SKD')
                <div class="col-md-12 mb-3">
                  <label class="form-label">
                    {{ $jenisSurat->kode_surat == 'UMUM' ? 'Keterangan' : 'Keperluan' }} <span class="text-danger">*</span>
                  </label>
                  <textarea name="keperluan" rows="3" class="form-control @error('keperluan') is-invalid @enderror" 
                            placeholder="{{ $jenisSurat->kode_surat == 'UMUM' ? 'Isi keterangan secara spesifik (Contoh: Orang tersebut diatas benar - benar meninggal dunia...)' : 'Jelaskan untuk keperluan apa surat ini dibutuhkan' }}" required>{{ old('keperluan') }}</textarea>
                  @error('keperluan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                @endif
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

<script>
  document.addEventListener('DOMContentLoaded', function() {
      if(typeof initAutosave === 'function') {
          initAutosave('formSurat', 'draft_surat');
      }
  });
</script>
@endsection
