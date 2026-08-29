<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FcmToken;
use Illuminate\Http\Request;

class FcmTokenController extends Controller
{
    /**
     * Save atau update FCM token
     */
    public function store(Request $request)
    {
        // Log request untuk debugging
        \Log::info('FCM Token API Called', [
            'user_id_request' => $request->user_id,
            'auth_id' => auth()->id(),
            'has_token' => !empty($request->token),
            'device_type' => $request->device_type,
            'ip' => $request->ip()
        ]);

        $request->validate([
            'token' => 'required|string',
            'device_type' => 'nullable|string',
            'browser' => 'nullable|string',
            'user_id' => 'nullable|integer|exists:users,id',
        ]);

        try {
            // Gunakan user_id dari request jika ada, fallback ke auth()->id()
            $userId = $request->user_id ?? auth()->id();
            
            \Log::info('FCM Token: Will save with user_id', ['user_id' => $userId]);

            // Check if token already exists
            $fcmToken = FcmToken::where('token', $request->token)->first();

            if ($fcmToken) {
                // Update existing token
                $fcmToken->update([
                    'user_id' => $userId,
                    'device_type' => $request->device_type ?? 'web',
                    'browser' => $request->browser,
                    'ip_address' => $request->ip(),
                    'last_used_at' => now(),
                ]);
                
                \Log::info('FCM Token: Updated existing token', ['id' => $fcmToken->id]);
            } else {
                // Create new token
                $newToken = FcmToken::create([
                    'user_id' => $userId,
                    'token' => $request->token,
                    'device_type' => $request->device_type ?? 'web',
                    'browser' => $request->browser,
                    'ip_address' => $request->ip(),
                    'last_used_at' => now(),
                ]);
                
                \Log::info('FCM Token: Created new token', ['id' => $newToken->id, 'user_id' => $userId]);
            }

            return response()->json([
                'success' => true,
                'message' => 'FCM token saved successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save FCM token: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete FCM token (saat user unsubscribe)
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        try {
            FcmToken::where('token', $request->token)->delete();

            return response()->json([
                'success' => true,
                'message' => 'FCM token deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete FCM token: ' . $e->getMessage(),
            ], 500);
        }
    }
}
