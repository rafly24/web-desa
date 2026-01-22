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

                        <h4 class="mb-3">{{ $laporan->judul_laporan }}</h4>

                        <table class="table table-bordered mb-4">
                            <tr>
                                <td width="30%"><strong>Nomor Laporan</strong></td>
                                <td>{{ $laporan->nomor_laporan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Pelapor</strong></td>
                                <td>{{ $laporan->is_anonim ? 'Anonim' : $laporan->nama_pelapor }}</td>
                            </tr>
                            @if(!$laporan->is_anonim)
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>{{ $laporan->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. Telepon</strong></td>
                                <td>{{ $laporan->no_telepon }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat Pelapor</strong></td>
                                <td>{{ $laporan->alamat }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Lokasi Kejadian</strong></td>
                                <td>{{ $laporan->lokasi_kejadian }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Kejadian</strong></td>
                                <td>{{ $laporan->tanggal_kejadian->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Laporan</strong></td>
                                <td>{{ $laporan->created_at->format('d F Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Prioritas</strong></td>
                                <td>{!! $laporan->prioritas_badge !!}</td>
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

                        @if($laporan->tanggapan_admin)
                        <div class="alert alert-success mt-4">
                            <h5><i class="ti ti-check"></i> Tanggapan Admin</h5>
                            <p style="white-space: pre-line;">{{ $laporan->tanggapan_admin }}</p>
                            @if($laporan->foto_tindak_lanjut)
                            <hr>
                            <strong>Foto Tindak Lanjut:</strong><br>
                            <a href="{{ asset('storage/' . $laporan->foto_tindak_lanjut) }}" target="_blank">
                                <img src="{{ asset('storage/' . $laporan->foto_tindak_lanjut) }}" 
                                     class="img-fluid img-thumbnail mt-2" style="max-height: 300px;">
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>

                    <!-- Update Status & Tanggapan -->
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="mb-3">Tindak Lanjut</h5>
                                
                                <form action="{{ route('admin.laporan-warga.update-status', $laporan->id) }}" 
                                      method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select" required>
                                            <option value="baru" {{ $laporan->status == 'baru' ? 'selected' : '' }}>Baru</option>
                                            <option value="diproses" {{ $laporan->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                            <option value="ditindaklanjuti" {{ $laporan->status == 'ditindaklanjuti' ? 'selected' : '' }}>Ditindaklanjuti</option>
                                            <option value="selesai" {{ $laporan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="ditolak" {{ $laporan->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Prioritas</label>
                                        <select name="prioritas" class="form-select">
                                            <option value="rendah" {{ $laporan->prioritas == 'rendah' ? 'selected' : '' }}>Rendah</option>
                                            <option value="sedang" {{ $laporan->prioritas == 'sedang' ? 'selected' : '' }}>Sedang</option>
                                            <option value="tinggi" {{ $laporan->prioritas == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tanggapan Admin</label>
                                        <textarea name="tanggapan_admin" rows="5" class="form-control" 
                                                  placeholder="Berikan tanggapan atau update tindak lanjut...">{{ $laporan->tanggapan_admin }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Foto Tindak Lanjut</label>
                                        <input type="file" name="foto_tindak_lanjut" class="form-control" accept="image/*">
                                        <small class="text-muted">Upload foto hasil tindak lanjut jika ada</small>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ti ti-device-floppy"></i> Simpan Tanggapan
                                    </button>
                                </form>

                                @if($laporan->admin)
                                <hr>
                                <small class="text-muted">
                                    <i class="ti ti-user"></i> Ditangani oleh: {{ $laporan->admin->name }}
                                </small>
                                @endif

                                @if($laporan->tanggal_ditanggapi)
                                <br>
                                <small class="text-muted">
                                    <i class="ti ti-calendar"></i> {{ $laporan->tanggal_ditanggapi->format('d M Y H:i') }}
                                </small>
                                @endif
                            </div>
                        </div>

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
