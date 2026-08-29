<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\PengajuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Barryvdh\DomPDF\Facade\Pdf;

class PengajuanSuratController extends Controller
{
    public function index()
    {
        $jenisSurat = JenisSurat::where('is_active', true)->get();
        return view('pengajuan-surat.index', compact('jenisSurat'));
    }

    public function create($kode_surat)
    {
        $jenisSurat = JenisSurat::where('kode_surat', $kode_surat)->where('is_active', true)->firstOrFail();
        return view('pengajuan-surat.create', compact('jenisSurat'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surat,id',
            'nik' => 'required|string|max:16',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'kebangsaan' => 'required|string|max:255',
            'agama' => 'required|string|max:255',
            'status_perkawinan' => 'required|string|max:255',
            'pekerjaan' => 'required|string|max:255',
            'alamat' => 'required|string',
        ]);

        $jenisSurat = JenisSurat::find($request->jenis_surat_id);
        
        $validatedKeperluan = $request->validate([
            'keperluan' => $jenisSurat && $jenisSurat->kode_surat == 'SKD' ? 'nullable|string' : 'required|string',
        ]);
        $validated = array_merge($validated, $validatedKeperluan);

        if ($jenisSurat && $jenisSurat->kode_surat == 'SKTM') {
            $validatedChild = $request->validate([
                'nik_anak' => 'required|string|max:16',
                'nama_anak' => 'required|string|max:255',
                'tempat_lahir_anak' => 'required|string|max:255',
                'tanggal_lahir_anak' => 'required|date',
                'jenis_kelamin_anak' => 'required|in:L,P',
                'pekerjaan_anak' => 'required|string|max:255',
                'alamat_anak' => 'required|string',
            ]);
            $validated = array_merge($validated, $validatedChild);
        }

        if (empty($validated['keperluan'])) {
            $validated['keperluan'] = 'Keperluan: ' . ($jenisSurat->nama_surat ?? 'Surat');
        }

        $validated['status'] = 'selesai';
        $validated['tanggal_selesai'] = now();
        


        $pengajuan = PengajuanSurat::create($validated);
        
        $pengajuan->update([
            'nomor_surat' => $pengajuan->nomor_pengajuan
        ]);

        // Auto-generate PDF dari template
        try {
            $pdfPath = $this->generateSuratPDF($pengajuan, $pengajuan->nomor_surat);
            $pengajuan->update(['file_surat_jadi' => $pdfPath]);
            
            // Langsung kembalikan file untuk diunduh tanpa menunggu admin
            return response()->download(storage_path('app/public/' . $pdfPath));

        } catch (\Exception $e) {
            Alert::error('Gagal!', 'Gagal generate PDF surat: ' . $e->getMessage());
            return redirect()->back();
        }
    }

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

        // Generate PDF menggunakan dompdf
        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        // Save PDF
        $filename = 'surat-' . $pengajuan->nomor_pengajuan . '.pdf';
        $path = 'surat-jadi/' . $filename;
        
        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }
}
