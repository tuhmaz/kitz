<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Article;
use App\Models\News;
use App\Models\SchoolClass;
use App\Models\Category;
use App\Models\Event;

class PerformanceOptimizationService
{
    /**
     * Cache duration in minutes
     */
    const CACHE_DURATION = 60; // 1 hour
    const CACHE_DURATION_LONG = 1440; // 24 hours
    const CACHE_DURATION_SHORT = 15; // 15 minutes

    /**
     * Get cached home page data
     */
    public function getCachedHomeData($database = 'jo')
    {
        $cacheKey = "home_data_{$database}";
        
        return Cache::store("{$database}_redis")->remember($cacheKey, self::CACHE_DURATION, function () use ($database) {
            return $this->buildHomeData($database);
        });
    }

    /**
     * Build home page data
     */
    private function buildHomeData($database)
    {
        try {
            // Get classes with minimal data
            $classes = SchoolClass::on($database)
                ->select('id', 'grade_name', 'grade_level')
                ->orderBy('grade_level')
                ->get();

            // Get categories with minimal data - skip if table doesn't exist
            $categories = collect();
            try {
                $categories = Category::on($database)
                    ->select('id', 'name')
                    ->orderBy('id')
                    ->get();
            } catch (\Exception $e) {
                // Categories table not found, skip
            }

            // Get latest news - skip if table doesn't exist
            $news = collect();
            try {
                $news = News::on($database)
                    ->select('id', 'title', 'content', 'created_at')
                    ->where('is_active', true)
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();
            } catch (\Exception $e) {
                // News table not found, skip
            }

            // Get recent articles - skip if table doesn't exist
            $articles = collect();
            try {
                $articles = Article::on($database)
                    ->select('id', 'title', 'grade_level', 'subject_id', 'created_at', 'visit_count')
                    ->where('status', 1)
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();
            } catch (\Exception $e) {
                // Articles table not found, skip
            }

            return [
                'classes' => $classes,
                'categories' => $categories,
                'news' => $news,
                'articles' => $articles,
                'cached_at' => now()
            ];

        } catch (\Exception $e) {
            Log::error('Error building home data', [
                'database' => $database,
                'error' => $e->getMessage()
            ]);
            
            return [
                'classes' => collect(),
                'categories' => collect(),
                'news' => collect(),
                'articles' => collect(),
                'cached_at' => now()
            ];
        }
    }

    /**
     * Get cached calendar events for a specific month
     */
    public function getCachedCalendarEvents($database, $year, $month)
    {
        $cacheKey = "calendar_events_{$database}_{$year}_{$month}";
        
        return Cache::store("{$database}_redis")->remember($cacheKey, self::CACHE_DURATION_LONG, function () use ($database, $year, $month) {
            return Event::on($database)
                ->select('id', 'title', 'description', 'event_date')
                ->whereYear('event_date', $year)
                ->whereMonth('event_date', $month)
                ->orderBy('event_date')
                ->get()
                ->groupBy(function($event) {
                    return $event->event_date->format('Y-m-d');
                });
        });
    }

    /**
     * Get cached statistics
     */
    public function getCachedStatistics($database)
    {
        $cacheKey = "statistics_{$database}";
        
        return Cache::store("{$database}_redis")->remember($cacheKey, self::CACHE_DURATION_SHORT, function () use ($database) {
            return $this->buildStatistics($database);
        });
    }

    /**
     * Build statistics data
     */
    private function buildStatistics($database)
    {
        try {
            $stats = [
                'total_articles' => Article::on($database)->where('status', 1)->count(),
                'total_classes' => SchoolClass::on($database)->count(),
                'total_news' => News::on($database)->where('is_active', true)->count(),
                'total_events' => Event::on($database)->count(),
            ];

            // Get file statistics
            $fileStats = DB::connection($database)
                ->table('files')
                ->join('articles', 'files.article_id', '=', 'articles.id')
                ->where('articles.status', 1)
                ->selectRaw('COUNT(*) as total_files, COUNT(DISTINCT articles.grade_level) as classes_with_files')
                ->first();

            $stats['total_files'] = $fileStats->total_files ?? 0;
            $stats['classes_with_files'] = $fileStats->classes_with_files ?? 0;
            $stats['file_availability_percentage'] = $stats['total_classes'] > 0 
                ? round(($stats['classes_with_files'] / $stats['total_classes']) * 100, 2)
                : 0;

            return $stats;

        } catch (\Exception $e) {
            Log::error('Error building statistics', [
                'database' => $database,
                'error' => $e->getMessage()
            ]);
            
            return [
                'total_articles' => 0,
                'total_classes' => 0,
                'total_news' => 0,
                'total_events' => 0,
                'total_files' => 0,
                'classes_with_files' => 0,
                'file_availability_percentage' => 0
            ];
        }
    }

    /**
     * Clear cache for specific database
     */
    public function clearDatabaseCache($database)
    {
        $patterns = [
            "home_data_{$database}",
            "statistics_{$database}",
            "calendar_events_{$database}_*"
        ];

        foreach ($patterns as $pattern) {
            if (str_contains($pattern, '*')) {
                // For wildcard patterns, we need to get all keys and delete matching ones
                $this->clearCachePattern($database, $pattern);
            } else {
                Cache::store("{$database}_redis")->forget($pattern);
            }
        }
    }

    /**
     * Clear cache with pattern matching
     */
    private function clearCachePattern($database, $pattern)
    {
        try {
            // Use Laravel's Redis facade instead of cache store getRedis
            $redis = \Illuminate\Support\Facades\Redis::connection("{$database}_redis");
            $prefix = config("database.redis.{$database}_redis.prefix", '');
            $searchPattern = $prefix . str_replace('*', '*', $pattern);
            
            // Get keys matching pattern
            $keys = $redis->keys($searchPattern);
            
            if (!empty($keys)) {
                $redis->del($keys);
            }
        } catch (\Exception $e) {
            Log::warning('Could not clear cache pattern', [
                'database' => $database,
                'pattern' => $pattern,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Warm up cache for all databases
     */
    public function warmUpCache()
    {
        $databases = ['jo', 'sa', 'eg', 'ps'];
        
        foreach ($databases as $database) {
            try {
                // Warm up home data
                $this->getCachedHomeData($database);
                
                // Warm up current month calendar
                $currentYear = now()->year;
                $currentMonth = now()->month;
                $this->getCachedCalendarEvents($database, $currentYear, $currentMonth);
                
                // Warm up statistics
                $this->getCachedStatistics($database);
                
                Log::info("Cache warmed up for database: {$database}");
                
            } catch (\Exception $e) {
                Log::error("Failed to warm up cache for database: {$database}", [
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Get cache health status
     */
    public function getCacheHealth()
    {
        $databases = ['jo', 'sa', 'eg', 'ps'];
        $health = [];
        
        foreach ($databases as $database) {
            try {
                // Use Laravel's Redis facade instead of cache store getRedis
                $redis = \Illuminate\Support\Facades\Redis::connection("{$database}_redis");
                $info = $redis->info();
                
                $health[$database] = [
                    'connected' => true,
                    'memory_used' => $info['used_memory_human'] ?? 'Unknown',
                    'keys_count' => $redis->dbsize(),
                    'last_check' => now()
                ];
            } catch (\Exception $e) {
                $health[$database] = [
                    'connected' => false,
                    'error' => $e->getMessage(),
                    'last_check' => now()
                ];
            }
        }
        
        return $health;
    }
}
