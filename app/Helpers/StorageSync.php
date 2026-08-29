<?php
// app/Helpers/StorageSync.php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StorageSync
{
    /**
     * Simpan file ke storage/app/public lalu otomatis copy ke public/storage
     */
    public static function storeAndSync($file, $folder = '')
    {
        try {
            // Test apakah disk public bisa diakses
            $testPath = 'test-file.txt';
            $testContent = 'test content';
            
            // Coba tulis file test
            $testResult = Storage::disk('public')->put($testPath, $testContent);
            
            if ($testResult) {
                // Disk public berfungsi, hapus file test
                Storage::disk('public')->delete($testPath);
                
                // Bersihkan folder path dari double slash
                $cleanFolder = rtrim($folder, '/');
                $fileName = uniqid() . '.webp';
                $path = $cleanFolder . '/' . $fileName;
                
                // Gunakan Intervention Image v3 untuk mengonversi ke WebP
                $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                $image = $manager->read($file);
                
                // Resize gambar (maksimal lebar 1200px) agar tidak terlalu berat, tapi tetap proporsional
                $image->scaleDown(width: 1200);
                
                $encoded = $image->toWebp(75); // Kualitas 75 sudah sangat bagus untuk WebP
                
                // Simpan file ke storage/app/public
                $saveResult = Storage::disk('public')->put($path, (string) $encoded);
                
                if (!$saveResult) {
                    return false;
                }
            } else {
                // Disk public bermasalah, gunakan disk local
                // dd('Disk public bermasalah, menggunakan disk local');
                
                $cleanFolder = rtrim($folder, '/');
                $fileName = uniqid() . '.webp';
                $path = $cleanFolder . '/' . $fileName;

                // Gunakan Intervention Image v3 untuk mengonversi ke WebP
                $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                $image = $manager->read($file);
                
                // Resize gambar (maksimal lebar 1200px) agar tidak terlalu berat, tapi tetap proporsional
                $image->scaleDown(width: 1200);
                
                $encoded = $image->toWebp(75); // Kualitas 75 sudah sangat bagus untuk WebP
                
                $saveResult = Storage::disk('local')->put($path, (string) $encoded);
                
                if (!$saveResult) {
                    return false;
                }
            }
            
            // Debug: cek path yang didapat
            // dd('Final path:', $path, 'File:', $file->getClientOriginalName());
            
            // Copy file ke public/storage agar bisa diakses dari web
            // Path untuk hosting (InfinityFree compatible)
            $source = storage_path('app/public/' . $path);
            $destination = public_path('storage/' . $path);
            
            // Debug: cek source dan destination
            // dd('Source:', $source, 'Destination:', $destination);
            
            // Buat direktori tujuan jika belum ada
            $destinationDir = dirname($destination);
            if (!file_exists($destinationDir)) {
                @mkdir($destinationDir, 0755, true);
            }
            
            // Copy file jika source ada dan bukan directory
            if (file_exists($source) && is_file($source)) {
                // Copy file dengan error handling
                $copyResult = @copy($source, $destination);
                
                // Jika copy gagal, coba dengan method lain
                if (!$copyResult) {
                    // Coba dengan file_get_contents dan file_put_contents
                    $fileContent = @file_get_contents($source);
                    if ($fileContent !== false) {
                        @file_put_contents($destination, $fileContent);
                    }
                }
                
                // Verifikasi file berhasil di-copy
                if (!file_exists($destination)) {
                    // Fallback: copy manual dengan stream
                    $sourceHandle = @fopen($source, 'rb');
                    $destHandle = @fopen($destination, 'wb');
                    
                    if ($sourceHandle && $destHandle) {
                        @stream_copy_to_stream($sourceHandle, $destHandle);
                        @fclose($sourceHandle);
                        @fclose($destHandle);
                    }
                }
                
                // Log hasil untuk debugging
                if (!file_exists($destination)) {
                    \Log::warning('StorageSync: Failed to copy file', [
                        'source' => $source,
                        'destination' => $destination,
                        'source_exists' => file_exists($source),
                        'dest_dir_writable' => is_writable($destinationDir)
                    ]);
                }
            } else {
                \Log::error('StorageSync: Source file not found', [
                    'source' => $source,
                    'path' => $path
                ]);
            }
            
            return $path;
            
        } catch (\Exception $e) {
            // Debug: cek error
            // dd('Error in StorageSync:', $e->getMessage());
            return false;
        }
    }

    /**
     * Hapus file dari storage dan public
     */
    public static function deleteAndSync($path)
    {
        if (!$path) return;

        try {
            // Hapus dari storage (jika ada)
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            
            // Hapus dari public/storage (jika ada)
            $publicPath = public_path('storage/' . $path);
            if (file_exists($publicPath) && is_file($publicPath)) {
                @unlink($publicPath);
            }
        } catch (\Exception $e) {
            // Log error tapi jangan stop execution
            Log::warning('StorageSync: Failed to delete file', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update file (hapus yang lama, simpan yang baru)
     */
    public static function updateAndSync($file, $oldPath, $folder = '')
    {
        // Hapus file lama jika ada
        if ($oldPath) {
            self::deleteAndSync($oldPath);
        }
        
        // Simpan file baru
        return self::storeAndSync($file, $folder);
    }
} 