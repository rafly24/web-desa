<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class PengajuanSuratController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function index()
    {
        $jenisSurat = JenisSurat::where('is_active', true)->get();
        return view('pengajuan-surat.index', compact('jenisSurat'));
    }

    public function create($kode_surat)
    {
        $jenisSurat = JenisSurat::where('kode_surat', $kode_surat)->where('is_active', true)->firstOrFail();
        $user = auth()->user();
        return view('pengajuan-surat.create', compact('jenisSurat', 'user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surat,id',
            'keperluan' => 'required|string',
            'file_ktp' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'file_kk' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'file_pendukung' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
        ]);

        $user = auth()->user();

        // Data otomatis dari user profile
        $validated['user_id'] = $user->id;
        $validated['nik'] = $user->nik;
        $validated['nama_lengkap'] = $user->name;
        $validated['tempat_lahir'] = $user->tempat_lahir;
        $validated['tanggal_lahir'] = $user->tanggal_lahir;
        $validated['jenis_kelamin'] = $user->jenis_kelamin == 'Laki-laki' ? 'L' : 'P';
        $validated['alamat'] = $user->alamat;
        $validated['rt_rw'] = $user->rt_rw;
        $validated['no_telepon'] = $user->no_telepon;
        $validated['desa_kelurahan'] = 'Karangduren'; // Default desa
        $validated['kecamatan'] = 'Kecamatan';
        $validated['kabupaten'] = 'Kabupaten';
        $validated['pekerjaan'] = '-'; // Optional

        // Upload files
        if ($request->hasFile('file_ktp')) {
            $validated['file_ktp'] = $request->file('file_ktp')->store('pengajuan-surat/ktp', 'public');
        }

        if ($request->hasFile('file_kk')) {
            $validated['file_kk'] = $request->file('file_kk')->store('pengajuan-surat/kk', 'public');
        }

        if ($request->hasFile('file_pendukung')) {
            $validated['file_pendukung'] = $request->file('file_pendukung')->store('pengajuan-surat/pendukung', 'public');
        }

        $pengajuan = PengajuanSurat::create($validated);

        Alert::success('Berhasil!', 'Pengajuan surat berhasil dikirim. Nomor pengajuan: ' . $pengajuan->nomor_pengajuan);
        return redirect()->route('warga.dashboard');
    }

    public function tracking(Request $request, $nomor = null)
    {
        $pengajuan = null;

        if ($nomor) {
            $pengajuan = PengajuanSurat::with('jenisSurat')->where('nomor_pengajuan', $nomor)->first();
        }

        return view('pengajuan-surat.tracking', compact('pengajuan'));
    }

    public function cekStatus(Request $request)
    {
        $request->validate([
            'nomor_pengajuan' => 'required|string',
        ]);

        return redirect()->route('pengajuan-surat.tracking', $request->nomor_pengajuan);
    }
}
