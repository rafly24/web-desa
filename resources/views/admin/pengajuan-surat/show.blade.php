@extends('admin.layouts.main')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-primary">
                <div class="row align-items-center">
                    <div class="col-6">
                        <h5 class="card-title fw-semibold text-white">Detail Pengajuan Surat</h5>
                    </div>
                    <div class="col-6 text-right">
                        <a href="{{ route('admin.pengajuan-surat.index') }}" class="btn btn-warning float-end">
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
                    <!-- Info Pengajuan -->
                    <div class="col-md-8">
                        <h5 class="mb-3">Informasi Pengajuan</h5>
                        <table class="table table-bordered">
                            <tr>
                                <td width="30%"><strong>Nomor Pengajuan</strong></td>
                                <td>{{ $pengajuan->nomor_pengajuan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Surat</strong></td>
                                <td>{{ $pengajuan->jenisSurat->nama_surat }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>{!! $pengajuan->status_badge !!}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Pengajuan</strong></td>
                                <td>{{ $pengajuan->created_at->format('d F Y H:i') }}</td>
                            </tr>
                        </table>

                        <h5 class="mb-3 mt-4">Data Pemohon</h5>
                        <table class="table table-bordered">
                            <tr>
                                <td width="30%"><strong>NIK</strong></td>
                                <td>{{ $pengajuan->nik }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama Lengkap</strong></td>
                                <td>{{ $pengajuan->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tempat, Tanggal Lahir</strong></td>
                                <td>{{ $pengajuan->tempat_lahir }}, {{ $pengajuan->tanggal_lahir->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Kelamin</strong></td>
                                <td>{{ $pengajuan->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Pekerjaan</strong></td>
                                <td>{{ $pengajuan->pekerjaan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td>{{ $pengajuan->alamat }}, RT/RW {{ $pengajuan->rt_rw }}<br>
                                    {{ $pengajuan->desa_kelurahan }}, {{ $pengajuan->kecamatan }}, {{ $pengajuan->kabupaten }}
                                </td>
                            </tr>
                            <tr>
                                <td><strong>No. Telepon</strong></td>
                                <td>{{ $pengajuan->no_telepon }}</td>
                            </tr>
                            <tr>
                                <td><strong>Keperluan</strong></td>
                                <td>{{ $pengajuan->keperluan }}</td>
                            </tr>
                        </table>

                        <h5 class="mb-3 mt-4">Dokumen</h5>
                        <div class="row">
                            @if($pengajuan->file_ktp)
                            <div class="col-md-4 mb-3">
                                <strong>KTP</strong><br>
                                <a href="{{ asset('storage/' . $pengajuan->file_ktp) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $pengajuan->file_ktp) }}" class="img-fluid img-thumbnail">
                                </a>
                            </div>
                            @endif
                            @if($pengajuan->file_kk)
                            <div class="col-md-4 mb-3">
                                <strong>Kartu Keluarga</strong><br>
                                <a href="{{ asset('storage/' . $pengajuan->file_kk) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $pengajuan->file_kk) }}" class="img-fluid img-thumbnail">
                                </a>
                            </div>
                            @endif
                            @if($pengajuan->file_pendukung)
                            <div class="col-md-4 mb-3">
                                <strong>Dokumen Pendukung</strong><br>
                                <a href="{{ asset('storage/' . $pengajuan->file_pendukung) }}" target="_blank" class="btn btn-primary">
                                    <i class="ti ti-download"></i> Download
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Update Status -->
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="mb-3">Update Status</h5>
                                
                                <form action="{{ route('admin.pengajuan-surat.update-status', $pengajuan->id) }}" 
                                      method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select" id="statusSelect" required>
                                            <option value="pending" {{ $pengajuan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="diproses" {{ $pengajuan->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                            <option value="selesai" {{ $pengajuan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="ditolak" {{ $pengajuan->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                        </select>
                                    </div>

                                    <div class="mb-3" id="nomorSuratField" style="display: {{ $pengajuan->status == 'selesai' ? 'block' : 'none' }};">
                                        <label class="form-label">Nomor Surat <span class="text-danger">*</span></label>
                                        <input type="text" name="nomor_surat" class="form-control" 
                                               value="{{ $pengajuan->nomor_surat }}"
                                               placeholder="Contoh: 474/001/XII/2024">
                                        <small class="text-muted">Format: Kode/Nomor/Bulan/Tahun</small>
                                        @if($pengajuan->nomor_surat)
                                        <div class="alert alert-info mt-2 mb-0 py-2">
                                            <small><i class="ti ti-info-circle"></i> Nomor saat ini: <strong>{{ $pengajuan->nomor_surat }}</strong></small>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Catatan Admin</label>
                                        <textarea name="catatan_admin" rows="3" class="form-control">{{ $pengajuan->catatan_admin }}</textarea>
                                    </div>

                                    @if($pengajuan->file_surat_jadi)
                                    <div class="alert alert-success mb-3">
                                        <i class="ti ti-file-check"></i> <strong>Surat Sudah Digenerate</strong><br>
                                        <a href="{{ asset('storage/' . $pengajuan->file_surat_jadi) }}" target="_blank" class="btn btn-sm btn-success mt-2">
                                            <i class="ti ti-download"></i> Download PDF
                                        </a>
                                    </div>
                                    @endif

                                    <div class="alert alert-info mb-3">
                                        <small>
                                            <i class="ti ti-info-circle"></i> <strong>Info:</strong><br>
                                            • Saat status "Selesai", nomor surat WAJIB diisi<br>
                                            • Sistem akan auto-generate PDF dari template<br>
                                            • Warga dapat langsung download surat
                                        </small>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ti ti-device-floppy"></i> Update Status
                                    </button>
                                </form>

                                <script>
                                    document.getElementById('statusSelect').addEventListener('change', function() {
                                        var nomorSuratField = document.getElementById('nomorSuratField');
                                        if (this.value === 'selesai') {
                                            nomorSuratField.style.display = 'block';
                                        } else {
                                            nomorSuratField.style.display = 'none';
                                        }
                                    });
                                </script>

                                @if($pengajuan->admin)
                                <hr>
                                <small class="text-muted">
                                    <i class="ti ti-user"></i> Diproses oleh: {{ $pengajuan->admin->name }}
                                </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
