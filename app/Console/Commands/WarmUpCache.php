<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PerformanceOptimizationService;

class WarmUpCache extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'cache:warmup {--database=all : Database to warm up (jo,sa,eg,ps,all)}';

    /**
     * The console command description.
     */
    protected $description = 'Warm up cache for better performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔥 Starting cache warm-up process...');
        
        $performanceService = new PerformanceOptimizationService();
        $database = $this->option('database');
        
        if ($database === 'all') {
            $this->info('Warming up cache for all databases...');
            $performanceService->warmUpCache();
        } else {
            $databases = ['jo', 'sa', 'eg', 'ps'];
            if (!in_array($database, $databases)) {
                $this->error("Invalid database. Available options: " . implode(', ', $databases) . ', all');
                return 1;
            }
            
            $this->info("Warming up cache for database: {$database}");
            $this->warmUpSingleDatabase($performanceService, $database);
        }
        
        $this->info('✅ Cache warm-up completed successfully!');
        
        // Show cache health
        $this->showCacheHealth($performanceService);
        
        return 0;
    }
    
    /**
     * Warm up cache for a single database
     */
    private function warmUpSingleDatabase($performanceService, $database)
    {
        try {
            // Warm up home data
            $this->line("  - Warming up home data for {$database}...");
            $performanceService->getCachedHomeData($database);
            
            // Warm up current month calendar
            $this->line("  - Warming up calendar data for {$database}...");
            $currentYear = now()->year;
            $currentMonth = now()->month;
            $performanceService->getCachedCalendarEvents($database, $currentYear, $currentMonth);
            
            // Warm up statistics
            $this->line("  - Warming up statistics for {$database}...");
            $performanceService->getCachedStatistics($database);
            
            $this->info("  ✅ Cache warmed up for database: {$database}");
            
        } catch (\Exception $e) {
            $this->error("  ❌ Failed to warm up cache for database: {$database}");
            $this->error("  Error: " . $e->getMessage());
        }
    }
    
    /**
     * Show cache health status
     */
    private function showCacheHealth($performanceService)
    {
        $this->line('');
        $this->info('📊 Cache Health Status:');
        
        try {
            $health = $performanceService->getCacheHealth();
            
            foreach ($health as $database => $status) {
                if ($status['connected']) {
                    $this->line("  🟢 {$database}: Connected - {$status['keys_count']} keys, Memory: {$status['memory_used']}");
                } else {
                    $this->line("  🔴 {$database}: Disconnected - {$status['error']}");
                }
            }
        } catch (\Exception $e) {
            $this->error('Failed to get cache health status: ' . $e->getMessage());
        }
    }
}
