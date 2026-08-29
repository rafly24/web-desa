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
                    <div class="col-md-12">
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
                                <td>{{ $pengajuan->alamat }}</td>
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


                </div>

            </div>
        </div>
    </div>
</div>
@endsection
