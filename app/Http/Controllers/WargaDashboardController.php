<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSurat;
use Illuminate\Http\Request;

class WargaDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        
        $pengajuanList = PengajuanSurat::with('jenisSurat')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => PengajuanSurat::where('user_id', $user->id)->count(),
            'pending' => PengajuanSurat::where('user_id', $user->id)->where('status', 'pending')->count(),
            'diproses' => PengajuanSurat::where('user_id', $user->id)->where('status', 'diproses')->count(),
            'selesai' => PengajuanSurat::where('user_id', $user->id)->where('status', 'selesai')->count(),
            'ditolak' => PengajuanSurat::where('user_id', $user->id)->where('status', 'ditolak')->count(),
        ];

        return view('warga.dashboard', compact('pengajuanList', 'stats', 'user'));
    }

    public function show($id)
    {
        $pengajuan = PengajuanSurat::with(['jenisSurat', 'admin'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('warga.pengajuan-detail', compact('pengajuan'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('warga.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nik' => 'required|digits:16|unique:users,nik,' . $user->id,
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'rt_rw' => 'required|string|max:10',
            'no_telepon' => 'required|string|max:15',
            'password' => 'nullable|min:6|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('warga.profile')->with('success', 'Profil berhasil diperbarui!');
    }
}
