<?php

namespace App\Http\Controllers;

use App\Models\KategoriLaporan;
use App\Models\LaporanWarga;
use App\Helpers\StorageSync;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class AdminLaporanWargaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $laporan = LaporanWarga::with(['kategoriLaporan', 'admin'])
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => LaporanWarga::count(),
            'baru' => LaporanWarga::where('status', 'baru')->count(),
            'diproses' => LaporanWarga::where('status', 'diproses')->count(),
            'ditindaklanjuti' => LaporanWarga::where('status', 'ditindaklanjuti')->count(),
            'selesai' => LaporanWarga::where('status', 'selesai')->count(),
        ];

        return view('admin.laporan-warga.index', compact('laporan', 'stats'));
    }

    public function show($id)
    {
        $laporan = LaporanWarga::with(['kategoriLaporan', 'admin'])->findOrFail($id);
        return view('admin.laporan-warga.show', compact('laporan'));
    }

    // UpdateStatus dihapus sesuai requirement (admin hanya readonly)

    public function destroy($id)
    {
        $laporan = LaporanWarga::findOrFail($id);

        // Delete files
        if ($laporan->foto_bukti) {
            foreach ($laporan->foto_bukti as $foto) {
                StorageSync::deleteAndSync($foto);
            }
        }

        $laporan->delete();

        Alert::success('Berhasil!', 'Laporan berhasil dihapus');
        return redirect()->route('admin.laporan-warga.index');
    }

    // Kategori Laporan Management
    public function kategori()
    {
        $kategori = KategoriLaporan::latest()->paginate(10);
        return view('admin.laporan-warga.kategori', compact('kategori'));
    }

    public function storeKategori(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'warna' => 'nullable|string|max:7',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        KategoriLaporan::create($validated);

        Alert::success('Berhasil!', 'Kategori laporan berhasil ditambahkan');
        return redirect()->back();
    }

    public function updateKategori(Request $request, $id)
    {
        $kategori = KategoriLaporan::findOrFail($id);

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'warna' => 'nullable|string|max:7',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $kategori->update($validated);

        Alert::success('Berhasil!', 'Kategori laporan berhasil diperbarui');
        return redirect()->back();
    }

    public function destroyKategori($id)
    {
        $kategori = KategoriLaporan::findOrFail($id);
        $kategori->delete();

        Alert::success('Berhasil!', 'Kategori laporan berhasil dihapus');
        return redirect()->back();
    }
}
