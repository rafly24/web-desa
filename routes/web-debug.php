<?php

use Illuminate\Support\Facades\Route;

// HANYA UNTUK DEBUG - HAPUS SETELAH SELESAI!
Route::get('/debug-log', function () {
    // Cek apakah user login dan admin
    $user = auth()->user();
    if (!$user || (isset($user->is_admin) && !$user->is_admin)) {
        return '<h1>403 - Silakan login sebagai Admin terlebih dahulu</h1>
                <a href="/login">Login Admin</a>';
    }
    
    $logPath = storage_path('logs/laravel.log');
    
    if (!file_exists($logPath)) {
        return '<h1>Log file tidak ditemukan</h1>';
    }
    
    // Ambil 100 baris terakhir
    $lines = file($logPath);
    $lastLines = array_slice($lines, -100);
    
    echo '<h1>Laravel Log (100 baris terakhir)</h1>';
    echo '<pre style="background: #1e1e1e; color: #fff; padding: 20px; overflow: auto; max-height: 80vh;">';
    echo htmlspecialchars(implode('', $lastLines));
    echo '</pre>';
    
    echo '<hr><h2>FCM Tokens Check</h2>';
    $tokens = \App\Models\FcmToken::with('user')->latest()->limit(10)->get();
    echo '<p><strong>Total tokens in database:</strong> ' . \App\Models\FcmToken::count() . '</p>';
    echo '<pre>';
    if ($tokens->count() > 0) {
        foreach ($tokens as $token) {
            $userName = $token->user ? $token->user->name : 'N/A';
            echo "User ID: {$token->user_id} | Name: {$userName} | Last Used: {$token->last_used_at}\n";
            echo "Token: " . substr($token->token, 0, 50) . "...\n";
            echo "Device: {$token->device_type} | Created: {$token->created_at}\n\n";
        }
    } else {
        echo "⚠️ NO TOKENS FOUND!\n\n";
        echo "Langkah untuk generate token baru:\n";
        echo "1. Pastikan sudah login sebagai warga/admin\n";
        echo "2. Klik tombol 'Aktifkan Notifikasi' di dashboard\n";
        echo "3. Allow notification permission di browser\n";
        echo "4. Refresh halaman ini untuk cek token\n";
    }
    echo '</pre>';
    
    echo '<hr><h2>Pengajuan Surat Check</h2>';
    $pengajuanList = \App\Models\PengajuanSurat::with('jenisSurat')->latest()->limit(5)->get();
    echo '<p><strong>Total pengajuan:</strong> ' . \App\Models\PengajuanSurat::count() . '</p>';
    echo '<p><strong>Pengajuan tanpa user_id:</strong> ' . \App\Models\PengajuanSurat::whereNull('user_id')->count() . '</p>';
    echo '<p><strong>Pengajuan dengan jenis_surat invalid:</strong> ' . \App\Models\PengajuanSurat::whereDoesntHave('jenisSurat')->count() . '</p>';
    echo '<pre>';
    foreach ($pengajuanList as $p) {
        $jenisSurat = $p->jenisSurat ? $p->jenisSurat->nama_surat : '⚠️ JENIS SURAT TIDAK DITEMUKAN (ID: ' . $p->jenis_surat_id . ')';
        $userId = $p->user_id ?? '⚠️ NULL';
        echo "ID: {$p->id} | User ID: {$userId} | Nama: {$p->nama_lengkap}\n";
        echo "Jenis: {$jenisSurat}\n";
        echo "Status: {$p->status} | Nomor: {$p->nomor_pengajuan}\n\n";
    }
    echo '</pre>';
    
    echo '<hr><h2>Jenis Surat Available</h2>';
    $jenisSuratList = \App\Models\JenisSurat::all();
    echo '<p><strong>Total jenis surat:</strong> ' . $jenisSuratList->count() . '</p>';
    echo '<pre>';
    foreach ($jenisSuratList as $js) {
        echo "ID: {$js->id} | Kode: {$js->kode_surat} | Nama: {$js->nama_surat}\n";
    }
    echo '</pre>';
    
    echo '<hr><h2>Firebase Config Check</h2>';
    echo '<pre>';
    $credentialsPath = env('FIREBASE_CREDENTIALS');
    echo "FIREBASE_CREDENTIALS path: {$credentialsPath}\n";
    echo "Base path: " . base_path() . "\n";
    
    if (!str_starts_with($credentialsPath, '/') && !preg_match('/^[A-Za-z]:/', $credentialsPath)) {
        $fullPath = base_path($credentialsPath);
    } else {
        $fullPath = $credentialsPath;
    }
    
    echo "Full path: {$fullPath}\n";
    echo "File exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
    echo "Is directory: " . (is_dir($fullPath) ? 'YES' : 'NO') . "\n";
    echo "Is file: " . (is_file($fullPath) ? 'YES' : 'NO') . "\n";
    
    if (file_exists($fullPath) && is_file($fullPath)) {
        echo "File size: " . filesize($fullPath) . " bytes\n";
    }
    echo '</pre>';
    
    echo '<hr><h2>Test Firebase Notification</h2>';
    echo '<p><strong>Current logged in user:</strong> ' . auth()->user()->name . ' (ID: ' . auth()->id() . ')</p>';
    echo '<form method="POST" action="/debug-send-notification">';
    echo csrf_field();
    echo '<label>User ID: <input type="number" name="user_id" value="' . auth()->id() . '" required></label><br>';
    echo '<label>Title: <input type="text" name="title" value="Test Notification" required></label><br>';
    echo '<label>Body: <input type="text" name="body" value="This is a test notification" required></label><br>';
    echo '<button type="submit" style="margin-top:10px; padding:8px 15px; background:#0d6efd; color:white; border:none; border-radius:5px; cursor:pointer;">Send Test Notification</button>';
    echo '</form>';
    
    echo '<hr><h2>Clean Up Invalid Tokens</h2>';
    echo '<form method="POST" action="/debug-cleanup-tokens">';
    echo csrf_field();
    echo '<p style="color: #ff6b6b;">⚠️ Ini akan menghapus SEMUA token FCM dari database. User harus aktifkan notifikasi lagi.</p>';
    echo '<button type="submit" style="margin-top:10px; padding:8px 15px; background:#dc3545; color:white; border:none; border-radius:5px; cursor:pointer;" onclick="return confirm(\'Yakin hapus semua token?\')">🗑️ Delete All Tokens</button>';
    echo '</form>';
});

// Test send notification
Route::post('/debug-send-notification', function(\Illuminate\Http\Request $request) {
    if (!auth()->check()) {
        return redirect('/login');
    }
    
    try {
        $fcmService = app(\App\Services\FcmService::class);
        $result = $fcmService->sendToUser(
            $request->user_id,
            $request->title,
            $request->body,
            ['type' => 'test']
        );
        
        if ($result) {
            return back()->with('success', 'Notification sent successfully!');
        } else {
            return back()->with('error', 'Failed to send notification. Check logs.');
        }
    } catch (\Exception $e) {
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
});

// Clean up invalid tokens
Route::post('/debug-cleanup-tokens', function() {
    if (!auth()->check()) {
        return redirect('/login');
    }
    
    try {
        $count = \App\Models\FcmToken::count();
        \App\Models\FcmToken::truncate(); // Hapus semua token
        
        \Log::info("FCM: All tokens deleted", ['count' => $count, 'by_user' => auth()->id()]);
        
        return redirect('/debug-log')->with('success', "✅ Berhasil menghapus {$count} token. Silakan aktifkan notifikasi lagi untuk generate token baru.");
    } catch (\Exception $e) {
        return redirect('/debug-log')->with('error', 'Error: ' . $e->getMessage());
    }
});

// Manual save token (untuk testing)
Route::post('/debug-save-token', function(\Illuminate\Http\Request $request) {
    if (!auth()->check()) {
        return response()->json(['error' => 'Not authenticated'], 401);
    }
    
    try {
        \Log::info('Debug Save Token Called', [
            'user_id' => auth()->id(),
            'has_token' => !empty($request->token)
        ]);
        
        $token = \App\Models\FcmToken::updateOrCreate(
            ['token' => $request->token],
            [
                'user_id' => auth()->id(),
                'device_type' => $request->device_type ?? 'web',
                'browser' => $request->browser,
                'ip_address' => $request->ip(),
                'last_used_at' => now(),
            ]
        );
        
        \Log::info('Debug Save Token Success', ['token_id' => $token->id]);
        
        return response()->json([
            'success' => true,
            'message' => 'Token saved via debug route',
            'token_id' => $token->id
        ]);
    } catch (\Exception $e) {
        \Log::error('Debug Save Token Error', ['error' => $e->getMessage()]);
        
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
});
