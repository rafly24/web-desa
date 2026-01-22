@extends('admin.layouts.main')

@section('content')
<div class="row">
    <div class="col-lg-12 d-flex align-items-strech">
      <div class="card w-100">
        <div class="card-header bg-primary">
            <div class="row align-items-center">
                <div class="col-6">
                    <h5 class="card-title fw-semibold text-white">Laporan Warga</h5>
                </div>
                <div class="col-6 text-right">
                    <a href="{{ route('admin.kategori-laporan.index') }}" type="button" class="btn btn-warning float-end">Kelola Kategori</a>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h3>{{ $stats['baru'] }}</h3>
                            <p class="mb-0">Laporan Baru</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h3>{{ $stats['diproses'] }}</h3>
                            <p class="mb-0">Diproses</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h3>{{ $stats['ditindaklanjuti'] }}</h3>
                            <p class="mb-0">Ditindaklanjuti</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h3>{{ $stats['selesai'] }}</h3>
                            <p class="mb-0">Selesai</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="table-responsive">
                    <table id="table_id" class="table display">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No. Laporan</th>
                                <th>Kategori</th>
                                <th>Judul</th>
                                <th>Pelapor</th>
                                <th>Prioritas</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporan as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $item->nomor_laporan }}</strong></td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $item->kategoriLaporan->warna }}">
                                            {{ $item->kategoriLaporan->nama_kategori }}
                                        </span>
                                    </td>
                                    <td>{{ \Str::limit($item->judul_laporan, 40) }}</td>
                                    <td>{{ $item->is_anonim ? 'Anonim' : $item->nama_pelapor }}</td>
                                    <td>{!! $item->prioritas_badge !!}</td>
                                    <td>{!! $item->status_badge !!}</td>
                                    <td>
                                        <a href="{{ route('admin.laporan-warga.show', $item->id) }}" 
                                           type="button" class="btn btn-info mb-1">
                                            <i class="ti ti-eye"></i> Detail
                                        </a>
                                        <form id="delete-{{ $item->id }}" 
                                              action="{{ route('admin.laporan-warga.destroy', $item->id) }}" 
                                              method="POST" class="d-inline">
                                            @method('delete')
                                            @csrf
                                            <button type="button" class="btn btn-danger swal-confirm mb-1" 
                                                    data-form="delete-{{ $item->id }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </td>                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3">
                {{ $laporan->links() }}
            </div>
        </div>
      </div>
    </div>
</div>
@endsection
