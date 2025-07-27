<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Services\PerformanceOptimizationService;

class PerformanceMonitor extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'performance:monitor {--database=all : Database to monitor (jo,sa,eg,ps,all)}';

    /**
     * The console command description.
     */
    protected $description = 'Monitor application performance and provide optimization recommendations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Performance Monitoring Report');
        $this->line('=====================================');
        
        $database = $this->option('database');
        $databases = $database === 'all' ? ['jo', 'sa', 'eg', 'ps'] : [$database];
        
        foreach ($databases as $db) {
            $this->monitorDatabase($db);
            $this->line('');
        }
        
        $this->showCacheStatus();
        $this->showSystemResources();
        $this->showRecommendations();
        
        return 0;
    }
    
    /**
     * Monitor specific database performance
     */
    private function monitorDatabase($database)
    {
        $this->line("📊 Database: {$database}");
        $this->line(str_repeat('-', 30));
        
        try {
            // Database connection test
            $startTime = microtime(true);
            DB::connection($database)->getPdo();
            $connectionTime = round((microtime(true) - $startTime) * 1000, 2);
            
            $this->line("  Connection Time: {$connectionTime}ms");
            
            // Table sizes
            $this->showTableSizes($database);
            
            // Query performance
            $this->analyzeQueryPerformance($database);
            
        } catch (\Exception $e) {
            $this->error("  ❌ Connection failed: " . $e->getMessage());
        }
    }
    
    /**
     * Show table sizes for optimization
     */
    private function showTableSizes($database)
    {
        try {
            $tables = ['articles', 'news', 'events', 'files', 'comments', 'school_classes'];
            
            foreach ($tables as $table) {
                $count = DB::connection($database)->table($table)->count();
                $this->line("  {$table}: {$count} records");
            }
            
        } catch (\Exception $e) {
            $this->warn("  Could not retrieve table sizes: " . $e->getMessage());
        }
    }
    
    /**
     * Analyze query performance
     */
    private function analyzeQueryPerformance($database)
    {
        try {
            // Test common queries
            $queries = [
                'Published Articles' => function() use ($database) {
                    return DB::connection($database)
                        ->table('articles')
                        ->where('status', 1)
                        ->count();
                },
                'Recent News' => function() use ($database) {
                    return DB::connection($database)
                        ->table('news')
                        ->where('is_active', 1)
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->count();
                },
                'This Month Events' => function() use ($database) {
                    return DB::connection($database)
                        ->table('events')
                        ->whereMonth('event_date', now()->month)
                        ->count();
                }
            ];
            
            foreach ($queries as $name => $query) {
                $startTime = microtime(true);
                $result = $query();
                $executionTime = round((microtime(true) - $startTime) * 1000, 2);
                
                $status = $executionTime < 50 ? '🟢' : ($executionTime < 100 ? '🟡' : '🔴');
                $this->line("  {$status} {$name}: {$executionTime}ms ({$result} records)");
            }
            
        } catch (\Exception $e) {
            $this->warn("  Could not analyze query performance: " . $e->getMessage());
        }
    }
    
    /**
     * Show cache status
     */
    private function showCacheStatus()
    {
        $this->line("💾 Cache Status");
        $this->line(str_repeat('-', 30));
        
        try {
            $performanceService = new PerformanceOptimizationService();
            $health = $performanceService->getCacheHealth();
            
            foreach ($health as $database => $status) {
                if ($status['connected']) {
                    $this->line("  🟢 {$database}: {$status['keys_count']} keys, Memory: {$status['memory_used']}");
                } else {
                    $this->line("  🔴 {$database}: Disconnected");
                }
            }
            
        } catch (\Exception $e) {
            $this->error("  Could not retrieve cache status: " . $e->getMessage());
        }
    }
    
    /**
     * Show system resources
     */
    private function showSystemResources()
    {
        $this->line("🖥️  System Resources");
        $this->line(str_repeat('-', 30));
        
        // Memory usage
        $memoryUsage = memory_get_usage(true);
        $memoryPeak = memory_get_peak_usage(true);
        $memoryLimit = ini_get('memory_limit');
        
        $this->line("  Memory Usage: " . $this->formatBytes($memoryUsage));
        $this->line("  Memory Peak: " . $this->formatBytes($memoryPeak));
        $this->line("  Memory Limit: {$memoryLimit}");
        
        // PHP version and extensions
        $this->line("  PHP Version: " . PHP_VERSION);
        $this->line("  Redis Extension: " . (extension_loaded('redis') ? '✅' : '❌'));
        $this->line("  PDO MySQL: " . (extension_loaded('pdo_mysql') ? '✅' : '❌'));
        $this->line("  OPcache: " . (extension_loaded('opcache') ? '✅' : '❌'));
    }
    
    /**
     * Show optimization recommendations
     */
    private function showRecommendations()
    {
        $this->line("💡 Optimization Recommendations");
        $this->line(str_repeat('-', 40));
        
        $recommendations = [];
        
        // Check memory usage
        $memoryUsage = memory_get_usage(true);
        if ($memoryUsage > 128 * 1024 * 1024) { // 128MB
            $recommendations[] = "Consider increasing memory_limit or optimizing memory usage";
        }
        
        // Check extensions
        if (!extension_loaded('opcache')) {
            $recommendations[] = "Enable OPcache for better PHP performance";
        }
        
        if (!extension_loaded('redis')) {
            $recommendations[] = "Install Redis extension for better caching";
        }
        
        // Check cache
        try {
            $performanceService = new PerformanceOptimizationService();
            $health = $performanceService->getCacheHealth();
            
            foreach ($health as $database => $status) {
                if (!$status['connected']) {
                    $recommendations[] = "Fix Redis connection for database: {$database}";
                }
            }
        } catch (\Exception $e) {
            $recommendations[] = "Check Redis configuration and connection";
        }
        
        if (empty($recommendations)) {
            $this->line("  ✅ No immediate optimizations needed!");
        } else {
            foreach ($recommendations as $i => $recommendation) {
                $this->line("  " . ($i + 1) . ". {$recommendation}");
            }
        }
        
        $this->line("");
        $this->info("💡 Pro Tips:");
        $this->line("  - Run 'php artisan cache:warmup' to improve initial load times");
        $this->line("  - Run 'php artisan migrate' to apply performance indexes");
        $this->line("  - Monitor slow queries in your application logs");
        $this->line("  - Consider using a CDN for static assets");
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $base = log($size, 1024);
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $units[floor($base)];
    }
}
