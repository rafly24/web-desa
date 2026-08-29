<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use App\Helpers\StorageSync;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminPengajuanSuratController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $pengajuan = PengajuanSurat::with('jenisSurat', 'admin')
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => PengajuanSurat::count(),
            'pending' => PengajuanSurat::where('status', 'pending')->count(),
            'diproses' => PengajuanSurat::where('status', 'diproses')->count(),
            'selesai' => PengajuanSurat::where('status', 'selesai')->count(),
            'ditolak' => PengajuanSurat::where('status', 'ditolak')->count(),
        ];

        return view('admin.pengajuan-surat.index', compact('pengajuan', 'stats'));
    }

    public function show($id)
    {
        $pengajuan = PengajuanSurat::with('jenisSurat', 'admin')->findOrFail($id);
        return view('admin.pengajuan-surat.show', compact('pengajuan'));
    }

    // generateSuratPDF & updateStatus dihapus sesuai requirement (admin hanya readonly dan PDF digenerate di publik)

    public function destroy($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        // Delete files
        if ($pengajuan->file_ktp) Storage::disk('public')->delete($pengajuan->file_ktp);
        if ($pengajuan->file_kk) Storage::disk('public')->delete($pengajuan->file_kk);
        if ($pengajuan->file_pendukung) Storage::disk('public')->delete($pengajuan->file_pendukung);
        if ($pengajuan->file_surat_jadi) Storage::disk('public')->delete($pengajuan->file_surat_jadi);

        $pengajuan->delete();

        Alert::success('Berhasil!', 'Pengajuan surat berhasil dihapus');
        return redirect()->route('admin.pengajuan-surat.index');
    }

    // Jenis Surat Management
    public function jenisSurat()
    {
        $jenisSurat = JenisSurat::latest()->paginate(10);
        return view('admin.pengajuan-surat.jenis-surat', compact('jenisSurat'));
    }

    public function storeJenisSurat(Request $request)
    {
        $validated = $request->validate([
            'nama_surat' => 'required|string|max:255',
            'kode_surat' => 'required|string|max:50|unique:jenis_surat,kode_surat',
            'persyaratan' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        JenisSurat::create($validated);

        Alert::success('Berhasil!', 'Jenis surat berhasil ditambahkan');
        return redirect()->back();
    }

    public function updateJenisSurat(Request $request, $id)
    {
        $jenisSurat = JenisSurat::findOrFail($id);

        $validated = $request->validate([
            'nama_surat' => 'required|string|max:255',
            'kode_surat' => 'required|string|max:50|unique:jenis_surat,kode_surat,' . $id,
            'persyaratan' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $jenisSurat->update($validated);

        Alert::success('Berhasil!', 'Jenis surat berhasil diperbarui');
        return redirect()->back();
    }

    public function destroyJenisSurat($id)
    {
        $jenisSurat = JenisSurat::findOrFail($id);
        $jenisSurat->delete();

        Alert::success('Berhasil!', 'Jenis surat berhasil dihapus');
        return redirect()->back();
    }
}
