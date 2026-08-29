<?php

namespace App\Services;

use App\Models\FcmToken;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class FcmService
{
    protected $messaging;

    public function __construct()
    {
        try {
            $credentialsPath = env('FIREBASE_CREDENTIALS');
            
            if (!$credentialsPath) {
                Log::warning('Firebase credentials not configured in .env');
                return;
            }

            // Pastikan path absolute
            if (!str_starts_with($credentialsPath, '/') && !preg_match('/^[A-Za-z]:/', $credentialsPath)) {
                $credentialsPath = base_path($credentialsPath);
            }

            // Validasi file exists dan bukan directory
            if (!file_exists($credentialsPath)) {
                Log::error('Firebase credentials file not found: ' . $credentialsPath);
                return;
            }

            if (is_dir($credentialsPath)) {
                Log::error('Firebase credentials path is a directory, not a file: ' . $credentialsPath);
                return;
            }

            $factory = (new Factory)->withServiceAccount($credentialsPath);
            $this->messaging = $factory->createMessaging();
            
            Log::info('Firebase initialized successfully');
        } catch (\Exception $e) {
            Log::error('Firebase initialization failed: ' . $e->getMessage());
        }
    }

    /**
     * Send notification ke single token
     */
    public function sendToToken(string $token, string $title, string $body, array $data = [])
    {
        if (!$this->messaging) {
            Log::warning('FCM: Firebase not initialized, skipping notification');
            return false;
        }

        try {
            $message = CloudMessage::withTarget('token', $token)
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            $result = $this->messaging->send($message);
            
            Log::info('FCM: Message sent successfully', [
                'token' => substr($token, 0, 20) . '...',
                'title' => $title,
                'result' => $result
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('FCM send to token failed: ' . $e->getMessage(), [
                'token' => substr($token, 0, 20) . '...',
                'title' => $title
            ]);
            return false;
        }
    }

    /**
     * Send notification ke multiple tokens
     */
    public function sendToMultipleTokens(array $tokens, string $title, string $body, array $data = [])
    {
        if (!$this->messaging) {
            Log::warning('FCM: Firebase not initialized, skipping notification');
            return false;
        }

        try {
            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            $result = $this->messaging->sendMulticast($message, $tokens);
            
            Log::info('FCM: Multicast sent', [
                'tokens_count' => count($tokens),
                'title' => $title,
                'success_count' => $result->successes()->count(),
                'failure_count' => $result->failures()->count()
            ]);
            
            // Log failures dan hapus token invalid
            if ($result->hasFailures()) {
                foreach ($result->failures()->getItems() as $failure) {
                    $errorMsg = $failure->error()->getMessage();
                    
                    Log::warning('FCM: Failed to send to token', [
                        'error' => $errorMsg
                    ]);
                    
                    // Hapus token jika NotRegistered atau InvalidRegistration
                    if (strpos($errorMsg, 'NotRegistered') !== false || 
                        strpos($errorMsg, 'InvalidRegistration') !== false) {
                        $invalidToken = $failure->target()->value();
                        FcmToken::where('token', $invalidToken)->delete();
                        Log::info('FCM: Deleted invalid token', [
                            'token' => substr($invalidToken, 0, 20) . '...'
                        ]);
                    }
                }
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('FCM send multicast failed: ' . $e->getMessage(), [
                'tokens_count' => count($tokens),
                'title' => $title
            ]);
            return false;
        }
    }

    /**
     * Send notification ke semua user (broadcast)
     */
    public function sendToAllUsers(string $title, string $body, array $data = [])
    {
        $tokens = FcmToken::getActiveTokens();
        
        if (empty($tokens)) {
            return false;
        }

        // FCM max 500 tokens per request, jadi kita chunk
        $chunks = array_chunk($tokens, 500);
        
        foreach ($chunks as $chunk) {
            $this->sendToMultipleTokens($chunk, $title, $body, $data);
        }

        return true;
    }

    /**
     * Send notification ke specific user
     */
    public function sendToUser($userId, string $title, string $body, array $data = [])
    {
        $tokens = FcmToken::where('user_id', $userId)
            ->where('last_used_at', '>=', now()->subDays(30))
            ->pluck('token')
            ->toArray();

        // Debug: log informasi
        Log::info('FCM sendToUser called', [
            'user_id' => $userId,
            'tokens_found' => count($tokens),
            'tokens' => $tokens,
            'title' => $title,
        ]);

        if (empty($tokens)) {
            Log::warning('FCM: No tokens found for user', ['user_id' => $userId]);
            return false;
        }

        return $this->sendToMultipleTokens($tokens, $title, $body, $data);
    }

    /**
     * Send notification untuk Update Status Pengajuan Surat
     */
    public function notifyPengajuanSuratStatus($pengajuanSurat, $newStatus)
    {
        // Cek apakah pengajuan punya user_id
        if (!$pengajuanSurat->user_id) {
            Log::warning('FCM: Pengajuan surat tidak memiliki user_id', ['pengajuan_id' => $pengajuanSurat->id]);
            return false;
        }

        // Load jenisSurat relationship jika belum di-load
        if (!$pengajuanSurat->relationLoaded('jenisSurat')) {
            $pengajuanSurat->load('jenisSurat');
        }

        $statusText = [
            'pending' => 'Menunggu Diproses',
            'diproses' => 'Sedang Diproses',
            'selesai' => 'Selesai - Silakan Ambil',
            'ditolak' => 'Ditolak',
        ];

        $jenisSuratNama = $pengajuanSurat->jenisSurat ? $pengajuanSurat->jenisSurat->nama_surat : 'Surat';
        $title = '📬 Update Status Pengajuan Surat';
        $body = "Pengajuan surat {$jenisSuratNama} - Status: " . ($statusText[$newStatus] ?? $newStatus);
        
        Log::info('FCM: Sending pengajuan surat notification', [
            'pengajuan_id' => $pengajuanSurat->id,
            'user_id' => $pengajuanSurat->user_id,
            'status' => $newStatus,
            'title' => $title,
            'body' => $body
        ]);
        
        $data = [
            'type' => 'pengajuan_surat',
            'pengajuan_id' => (string) $pengajuanSurat->id,
            'status' => $newStatus,
            'url' => route('pengajuan-surat.tracking', $pengajuanSurat->nomor_pengajuan),
        ];

        return $this->sendToUser($pengajuanSurat->user_id, $title, $body, $data);
    }

    /**
     * Send notification untuk Update Status Laporan Warga
     */
    public function notifyLaporanWargaStatus($laporanWarga, $newStatus)
    {
        // Cek apakah laporan punya user_id
        if (!$laporanWarga->user_id) {
            Log::warning('FCM: Laporan tidak memiliki user_id', ['laporan_id' => $laporanWarga->id]);
            return false;
        }

        $statusText = [
            'baru' => 'Laporan Diterima',
            'diproses' => 'Sedang Diproses',
            'ditindaklanjuti' => 'Sedang Ditindaklanjuti',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
        ];

        $judul = $laporanWarga->judul ?? 'Laporan Warga';
        $title = '📊 Update Status Laporan';
        $body = "Laporan Anda ({$judul}) - Status: " . ($statusText[$newStatus] ?? $newStatus);
        
        $data = [
            'type' => 'laporan_warga',
            'laporan_id' => (string) $laporanWarga->id,
            'status' => $newStatus,
        ];

        return $this->sendToUser($laporanWarga->user_id, $title, $body, $data);
    }

    /**
     * Send notification untuk Berita Baru
     */
    public function notifyNewBerita($berita)
    {
        $title = '📰 Berita Baru: ' . $berita->judul;
        $body = strip_tags(substr($berita->konten, 0, 100)) . '...';
        
        $data = [
            'type' => 'berita',
            'berita_id' => (string) $berita->id,
            'url' => route('berita.detail', $berita->slug),
        ];

        return $this->sendToAllUsers($title, $body, $data);
    }

    /**
     * Send notification untuk Pengumuman Baru
     */
    public function notifyNewPengumuman($pengumuman)
    {
        $title = '📢 Pengumuman Penting!';
        $body = $pengumuman->judul;
        
        $data = [
            'type' => 'pengumuman',
            'pengumuman_id' => (string) $pengumuman->id,
            'url' => url('/pengumuman'),
        ];

        return $this->sendToAllUsers($title, $body, $data);
    }
}
