<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add missing user_id foreign keys
        $tables = [
            'video_profils', 'kategoris', 'wilayahs', 
            'sliders', 'comment_replies', 'comments', 
            'announcements', 'situses'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'user_id')) {
                try {
                    \Illuminate\Support\Facades\DB::statement("ALTER TABLE `{$tableName}` ADD CONSTRAINT `{$tableName}_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;");
                } catch (\Exception $e) {
                    // Ignore if already exists or type mismatch
                }
            }
        }

        // Add foreign key for comments -> beritas
        if (Schema::hasTable('comments') && Schema::hasColumn('comments', 'berita_id')) {
            try {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE `comments` ADD CONSTRAINT `comments_berita_id_foreign` FOREIGN KEY (`berita_id`) REFERENCES `beritas` (`id`) ON DELETE CASCADE;");
            } catch (\Exception $e) {}
        }

        // Add foreign key for comment_replies -> comments
        if (Schema::hasTable('comment_replies') && Schema::hasColumn('comment_replies', 'comment_id')) {
            try {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE `comment_replies` ADD CONSTRAINT `comment_replies_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE;");
            } catch (\Exception $e) {}
        }
    }

    public function down(): void
    {
        //
    }
};
