<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResponseOptimization
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // تطبيق التحسينات على الاستجابة
        $this->optimizeResponse($response, $request);
        
        return $response;
    }
    
    /**
     * تحسين الاستجابة
     */
    private function optimizeResponse($response, $request)
    {
        // إضافة headers للأداء
        $this->addPerformanceHeaders($response);
        
        // تحسين التخزين المؤقت
        $this->optimizeCaching($response, $request);
        
        // ضغط المحتوى إذا كان مناسباً
        $this->compressResponse($response);
        
        // إضافة معلومات الأداء للتطوير
        if (config('app.debug')) {
            $this->addDebugHeaders($response);
        }
    }
    
    /**
     * إضافة headers للأداء
     */
    private function addPerformanceHeaders($response)
    {
        // DNS prefetch control
        $response->headers->set('X-DNS-Prefetch-Control', 'on');
        
        // Preconnect to external domains
        $response->headers->set('Link', '<https://fonts.googleapis.com>; rel=preconnect, <https://fonts.gstatic.com>; rel=preconnect', false);
        
        // Security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Performance hints
        $response->headers->set('Accept-CH', 'DPR, Viewport-Width, Width');
    }
    
    /**
     * تحسين التخزين المؤقت
     */
    private function optimizeCaching($response, $request)
    {
        $contentType = $response->headers->get('Content-Type', '');
        
        // تحديد مدة التخزين المؤقت حسب نوع المحتوى
        if (str_contains($contentType, 'text/html')) {
            // HTML pages - short cache for dynamic content
            $response->headers->set('Cache-Control', 'public, max-age=300, must-revalidate'); // 5 minutes
        } elseif (str_contains($contentType, 'application/json')) {
            // API responses - very short cache
            $response->headers->set('Cache-Control', 'public, max-age=60, must-revalidate'); // 1 minute
        } elseif (str_contains($contentType, 'text/css') || str_contains($contentType, 'javascript')) {
            // CSS/JS files - long cache
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable'); // 1 year
        } elseif (str_contains($contentType, 'image/')) {
            // Images - medium cache
            $response->headers->set('Cache-Control', 'public, max-age=2592000'); // 30 days
        }
        
        // إضافة ETag للتحقق من التغييرات
        if (!$response->headers->has('ETag') && $response->getContent()) {
            $etag = md5($response->getContent());
            $response->headers->set('ETag', '"' . $etag . '"');
            
            // التحقق من If-None-Match header
            if ($request->header('If-None-Match') === '"' . $etag . '"') {
                $response->setStatusCode(304);
                $response->setContent('');
            }
        }
    }
    
    /**
     * ضغط المحتوى
     */
    private function compressResponse($response)
    {
        $contentType = $response->headers->get('Content-Type', '');
        $content = $response->getContent();
        
        // تحديد أنواع المحتوى القابلة للضغط
        $compressibleTypes = [
            'text/html',
            'text/css',
            'text/javascript',
            'application/javascript',
            'application/json',
            'text/plain',
            'application/xml',
            'text/xml'
        ];
        
        // التحقق من إمكانية الضغط
        $shouldCompress = false;
        foreach ($compressibleTypes as $type) {
            if (str_contains($contentType, $type)) {
                $shouldCompress = true;
                break;
            }
        }
        
        // ضغط المحتوى إذا كان مناسباً
        if ($shouldCompress && strlen($content) > 1024 && function_exists('gzencode')) {
            // التحقق من دعم المتصفح للضغط
            $acceptEncoding = $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '';
            
            if (str_contains($acceptEncoding, 'gzip')) {
                $compressedContent = gzencode($content, 6);
                if ($compressedContent !== false) {
                    $response->setContent($compressedContent);
                    $response->headers->set('Content-Encoding', 'gzip');
                    $response->headers->set('Content-Length', strlen($compressedContent));
                }
            }
        }
    }
    
    /**
     * إضافة معلومات التطوير
     */
    private function addDebugHeaders($response)
    {
        // معلومات الذاكرة
        $memoryUsage = memory_get_usage(true);
        $memoryPeak = memory_get_peak_usage(true);
        
        $response->headers->set('X-Memory-Usage', $this->formatBytes($memoryUsage));
        $response->headers->set('X-Memory-Peak', $this->formatBytes($memoryPeak));
        
        // معلومات قاعدة البيانات
        if (class_exists('Illuminate\Support\Facades\DB')) {
            $queryCount = count(\Illuminate\Support\Facades\DB::getQueryLog());
            $response->headers->set('X-Database-Queries', $queryCount);
        }
        
        // وقت التنفيذ
        if (defined('LARAVEL_START')) {
            $executionTime = round((microtime(true) - LARAVEL_START) * 1000, 2);
            $response->headers->set('X-Execution-Time', $executionTime . 'ms');
        }
    }
    
    /**
     * تنسيق حجم الذاكرة
     */
    private function formatBytes($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $base = log($size, 1024);
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $units[floor($base)];
    }
}
