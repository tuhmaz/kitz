<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseOptimization
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // تحسين إعدادات قاعدة البيانات للأداء
        $this->optimizeDatabaseSettings();
        
        // تشغيل الطلب
        $response = $next($request);
        
        // تسجيل استعلامات قاعدة البيانات في بيئة التطوير
        if (config('app.debug') && config('app.env') === 'local') {
            $this->logSlowQueries();
        }
        
        return $response;
    }
    
    /**
     * تحسين إعدادات قاعدة البيانات
     */
    private function optimizeDatabaseSettings()
    {
        try {
            // الحصول على قاعدة البيانات الحالية
            $database = session('database', config('database.default'));
            
            // تحسين إعدادات MySQL للأداء
            $optimizations = [
                'SET SESSION query_cache_type = ON',
                'SET SESSION query_cache_size = 67108864', // 64MB
                'SET SESSION innodb_buffer_pool_size = 134217728', // 128MB
                'SET SESSION max_connections = 100',
                'SET SESSION wait_timeout = 28800',
                'SET SESSION interactive_timeout = 28800'
            ];
            
            foreach ($optimizations as $query) {
                try {
                    DB::connection($database)->statement($query);
                } catch (\Exception $e) {
                    // تجاهل الأخطاء التي قد تحدث مع بعض إعدادات MySQL
                    Log::debug("Database optimization query failed: {$query}", [
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
        } catch (\Exception $e) {
            Log::warning('Failed to apply database optimizations', [
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * تسجيل الاستعلامات البطيئة
     */
    private function logSlowQueries()
    {
        $queries = DB::getQueryLog();
        
        foreach ($queries as $query) {
            // تسجيل الاستعلامات التي تستغرق أكثر من 100ms
            if ($query['time'] > 100) {
                Log::warning('Slow query detected', [
                    'sql' => $query['query'],
                    'bindings' => $query['bindings'],
                    'time' => $query['time'] . 'ms'
                ]);
            }
        }
    }
}
