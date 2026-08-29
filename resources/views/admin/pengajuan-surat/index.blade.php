@extends('admin.layouts.main')

@section('content')
<div class="row">
    <div class="col-lg-12 d-flex align-items-strech">
      <div class="card w-100">
        <div class="card-header bg-primary">
            <div class="row align-items-center">
                <div class="col-6">
                    <h5 class="card-title fw-semibold text-white">Pengajuan Surat Online</h5>
                </div>
                <div class="col-6 text-right">
                    <a href="{{ route('admin.jenis-surat.index') }}" type="button" class="btn btn-warning float-end">Kelola Jenis Surat</a>
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
                <div class="table-responsive">
                    <table id="table_id" class="table display">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No. Pengajuan</th>
                                <th>Jenis Surat</th>
                                <th>Nama Pemohon</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengajuan as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $item->nomor_pengajuan }}</strong></td>
                                    <td>{{ $item->jenisSurat->nama_surat }}</td>
                                    <td>{{ $item->nama_lengkap }}</td>
                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                    <td>{!! $item->status_badge !!}</td>
                                    <td>
                                        <a href="{{ route('admin.pengajuan-surat.show', $item->id) }}" 
                                           type="button" class="btn btn-info mb-1">
                                            <i class="ti ti-eye"></i> Detail
                                        </a>
                                        <form id="delete-{{ $item->id }}" 
                                              action="{{ route('admin.pengajuan-surat.destroy', $item->id) }}" 
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
                {{ $pengajuan->links() }}
            </div>
        </div>
      </div>
    </div>
</div>
@endsection
