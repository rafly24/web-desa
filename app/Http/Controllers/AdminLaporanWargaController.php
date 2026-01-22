<?php

namespace App\Http\Controllers;

use App\Models\KategoriLaporan;
use App\Models\LaporanWarga;
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

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:baru,diproses,ditindaklanjuti,selesai,ditolak',
            'prioritas' => 'nullable|in:rendah,sedang,tinggi',
            'tanggapan_admin' => 'nullable|string',
            'foto_tindak_lanjut' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $laporan = LaporanWarga::findOrFail($id);

        $data = [
            'status' => $request->status,
            'tanggapan_admin' => $request->tanggapan_admin,
            'ditangani_oleh' => Auth::id(),
        ];

        if ($request->has('prioritas')) {
            $data['prioritas'] = $request->prioritas;
        }

        if ($request->status === 'diproses' && $laporan->status === 'baru') {
            $data['tanggal_ditanggapi'] = now();
        }

        if ($request->status === 'selesai') {
            $data['tanggal_selesai'] = now();
        }

        if ($request->hasFile('foto_tindak_lanjut')) {
            if ($laporan->foto_tindak_lanjut) {
                Storage::disk('public')->delete($laporan->foto_tindak_lanjut);
            }
            $data['foto_tindak_lanjut'] = $request->file('foto_tindak_lanjut')->store('laporan-tindak-lanjut', 'public');
        }

        $laporan->update($data);

        Alert::success('Berhasil!', 'Status laporan berhasil diperbarui');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $laporan = LaporanWarga::findOrFail($id);

        // Delete files
        if ($laporan->foto_bukti) {
            foreach ($laporan->foto_bukti as $foto) {
                Storage::disk('public')->delete($foto);
            }
        }
        if ($laporan->foto_tindak_lanjut) {
            Storage::disk('public')->delete($laporan->foto_tindak_lanjut);
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
