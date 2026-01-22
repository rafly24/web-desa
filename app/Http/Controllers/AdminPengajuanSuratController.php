<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

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

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,selesai,ditolak',
            'nomor_surat' => 'required_if:status,selesai|nullable|string',
            'catatan_admin' => 'nullable|string',
        ]);

        $pengajuan = PengajuanSurat::findOrFail($id);

        $data = [
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
            'diproses_oleh' => Auth::id(),
        ];

        if ($request->status === 'diproses' && $pengajuan->status === 'pending') {
            $data['tanggal_diproses'] = now();
        }

        if ($request->status === 'selesai') {
            // Validasi nomor surat harus diisi
            if (empty($request->nomor_surat)) {
                Alert::error('Gagal!', 'Nomor surat harus diisi untuk status selesai');
                return redirect()->back();
            }

            $data['nomor_surat'] = $request->nomor_surat;
            $data['tanggal_selesai'] = now();

            // Auto-generate PDF dari template
            try {
                $pdfPath = $this->generateSuratPDF($pengajuan, $request->nomor_surat);
                $data['file_surat_jadi'] = $pdfPath;
            } catch (\Exception $e) {
                Alert::error('Gagal!', 'Gagal generate PDF: ' . $e->getMessage());
                return redirect()->back();
            }
        }

        $pengajuan->update($data);

        Alert::success('Berhasil!', 'Status pengajuan berhasil diperbarui');
        return redirect()->back();
    }

    /**
     * Generate PDF surat dari template
     */
    private function generateSuratPDF($pengajuan, $nomor_surat)
    {
        $pengajuan->load('jenisSurat');
        $kode = strtolower($pengajuan->jenisSurat->kode_surat);

        // Cek apakah template tersedia
        $templatePath = "templates.surat.{$kode}";
        if (!view()->exists($templatePath)) {
            throw new \Exception("Template surat untuk {$kode} tidak tersedia");
        }

        // Render HTML dari template
        $html = view($templatePath, [
            'pengajuan' => $pengajuan,
            'nomor_surat' => $nomor_surat,
        ])->render();

        // Generate PDF menggunakan dompdf (install: composer require barryvdh/laravel-dompdf)
        $pdf = \PDF::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        // Save PDF
        $filename = 'surat-' . $pengajuan->nomor_pengajuan . '.pdf';
        $path = 'surat-jadi/' . $filename;
        
        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }

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
