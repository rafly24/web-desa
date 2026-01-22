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
            'email' => 'nullable|email',
            'no_telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
            'judul_laporan' => 'required|string|max:255',
            'isi_laporan' => 'required|string',
            'lokasi_kejadian' => 'required|string',
            'tanggal_kejadian' => 'required|date',
            'foto_bukti.*' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'is_anonim' => 'nullable|boolean',
        ]);

        // Upload multiple photos
        $fotoBukti = [];
        if ($request->hasFile('foto_bukti')) {
            foreach ($request->file('foto_bukti') as $foto) {
                $fotoBukti[] = $foto->store('laporan-warga', 'public');
            }
        }
        $validated['foto_bukti'] = $fotoBukti;
        $validated['is_anonim'] = $request->has('is_anonim') ? true : false;

        $laporan = LaporanWarga::create($validated);

        Alert::success('Berhasil!', 'Laporan Anda berhasil dikirim. Nomor laporan: ' . $laporan->nomor_laporan);
        return redirect()->route('laporan-warga.detail', $laporan->id);
    }

    public function detail($id)
    {
        $laporan = LaporanWarga::with(['kategoriLaporan', 'admin'])->findOrFail($id);
        
        // Increment views
        $laporan->increment('views');

        return view('laporan-warga.detail', compact('laporan'));
    }

    public function tracking(Request $request, $nomor = null)
    {
        $laporan = null;

        if ($nomor) {
            $laporan = LaporanWarga::with('kategoriLaporan')->where('nomor_laporan', $nomor)->first();
        }

        return view('laporan-warga.tracking', compact('laporan'));
    }

    public function cekStatus(Request $request)
    {
        $request->validate([
            'nomor_laporan' => 'required|string',
        ]);

        return redirect()->route('laporan-warga.tracking', $request->nomor_laporan);
    }
}
