<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CleanExpiredSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:clean {--force : Force cleanup without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean expired session files and optimize session storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting session cleanup...');

        $sessionDriver = config('session.driver');
        $cleaned = 0;

        switch ($sessionDriver) {
            case 'file':
                $cleaned = $this->cleanFileBasedSessions();
                break;
            case 'database':
                $cleaned = $this->cleanDatabaseSessions();
                break;
            case 'redis':
                $cleaned = $this->cleanRedisSessions();
                break;
            default:
                $this->warn("Session driver '{$sessionDriver}' cleanup not implemented.");
                return 1;
        }

        $this->info("Session cleanup completed. Removed {$cleaned} expired sessions.");
        
        // تنظيف إضافي
        $this->cleanTemporaryFiles();
        
        return 0;
    }

    /**
     * Clean file-based sessions
     */
    protected function cleanFileBasedSessions(): int
    {
        $sessionPath = storage_path('framework/sessions');
        
        if (!File::exists($sessionPath)) {
            $this->warn('Session directory does not exist.');
            return 0;
        }

        $files = File::files($sessionPath);
        $lifetime = config('session.lifetime') * 60; // Convert to seconds
        $cleaned = 0;
        $now = time();

        foreach ($files as $file) {
            $lastModified = File::lastModified($file);
            
            if (($now - $lastModified) > $lifetime) {
                if (File::delete($file)) {
                    $cleaned++;
                }
            }
        }

        return $cleaned;
    }

    /**
     * Clean database sessions
     */
    protected function cleanDatabaseSessions(): int
    {
        $table = config('session.table', 'sessions');
        $lifetime = config('session.lifetime') * 60;
        
        try {
            $cleaned = DB::table($table)
                ->where('last_activity', '<', time() - $lifetime)
                ->delete();
                
            return $cleaned;
        } catch (\Exception $e) {
            $this->error("Failed to clean database sessions: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Clean Redis sessions
     */
    protected function cleanRedisSessions(): int
    {
        try {
            $redis = app('redis')->connection(config('session.connection'));
            $prefix = config('session.cookie') . ':';
            $keys = $redis->keys($prefix . '*');
            $cleaned = 0;
            
            foreach ($keys as $key) {
                $ttl = $redis->ttl($key);
                if ($ttl === -1 || $ttl === -2) {
                    $redis->del($key);
                    $cleaned++;
                }
            }
            
            return $cleaned;
        } catch (\Exception $e) {
            $this->error("Failed to clean Redis sessions: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Clean temporary files
     */
    protected function cleanTemporaryFiles(): void
    {
        $this->info('Cleaning temporary files...');
        
        // تنظيف ملفات التحميل المؤقتة
        $tempPath = storage_path('app/temp');
        if (File::exists($tempPath)) {
            $files = File::files($tempPath);
            $cleaned = 0;
            
            foreach ($files as $file) {
                $lastModified = File::lastModified($file);
                if ((time() - $lastModified) > 3600) { // أقدم من ساعة
                    if (File::delete($file)) {
                        $cleaned++;
                    }
                }
            }
            
            if ($cleaned > 0) {
                $this->info("Cleaned {$cleaned} temporary files.");
            }
        }

        // تنظيف cache files قديمة
        $this->call('cache:clear');
        $this->call('view:clear');
        $this->call('route:clear');
        $this->call('config:clear');
    }
}
