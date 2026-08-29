<?php

namespace App\Http\Controllers;

use App\Models\KategoriLaporan;
use App\Models\LaporanWarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class LaporanWargaController extends Controller
{
    public function index()
    {
        $query = LaporanWarga::with('kategoriLaporan');

        // Filter by category if provided
        if (request('kategori')) {
            $query->where('kategori_laporan_id', request('kategori'));
        }

        $laporanList = $query->latest()->paginate(10);

        $kategori = KategoriLaporan::where('is_active', true)->get();

        return view('laporan-warga.index', compact('laporanList', 'kategori'));
    }

    public function create()
    {
        $kategori = KategoriLaporan::where('is_active', true)->get();
        return view('laporan-warga.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_laporan_id' => 'required|exists:kategori_laporan,id',
            'nama_pelapor' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:15',
            'isi_laporan' => 'required|string',
            'foto_bukti.*' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        // Upload multiple photos
        $fotoBukti = [];
        if ($request->hasFile('foto_bukti')) {
            foreach ($request->file('foto_bukti') as $foto) {
                $fotoBukti[] = $foto->store('laporan-warga', 'public');
            }
        }
        $validated['foto_bukti'] = $fotoBukti;

        // Status default
        $validated['status'] = 'baru';

        $laporan = LaporanWarga::create($validated);

        Alert::success('Berhasil!', 'Laporan Anda berhasil dikirim dan akan segera dilihat oleh Admin. Nomor Laporan: ' . $laporan->nomor_laporan);
        return redirect()->route('laporan-warga.index');
    }

    public function detail($id)
    {
        $laporan = LaporanWarga::with(['kategoriLaporan', 'admin'])->findOrFail($id);
        
        // Increment views
        $laporan->increment('views');

        return view('laporan-warga.detail', compact('laporan'));
    }
}
