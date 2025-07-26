<?php

use App\Http\Controllers\SecurityLogController;
use App\Http\Controllers\SecurityAnalyticsController;
use App\Http\Controllers\IpManagementController;
use App\Http\Controllers\RateLimitLogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Security Dashboard Routes
|--------------------------------------------------------------------------
|
| These routes handle security dashboard functionality with proper
| rate limiting, permission checks, and improved architecture.
|
*/

Route::middleware(['auth', 'verified', 'security.permission'])->prefix('dashboard/security')->name('dashboard.security.')->group(function () {
    
    // Main security dashboard
    Route::get('/', [SecurityLogController::class, 'index'])->name('index');
    
    // Security logs
    Route::get('/logs', [SecurityLogController::class, 'logs'])->name('logs');
    Route::get('/export', [SecurityLogController::class, 'export'])
        ->middleware('throttle:5,60') // 5 exports per hour
        ->name('export');
    
    // Log management
    Route::post('/logs/{log}/resolve', [SecurityLogController::class, 'resolve'])->name('logs.resolve');
    Route::delete('/logs/{log}', [SecurityLogController::class, 'destroy'])->name('logs.destroy');
    
    // Analytics Routes (New Specialized Controller)
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [SecurityAnalyticsController::class, 'index'])->name('index');
        Route::get('/real-time-metrics', [SecurityAnalyticsController::class, 'realTimeMetrics'])->name('real-time-metrics');
        Route::get('/threat-intelligence', [SecurityAnalyticsController::class, 'threatIntelligence'])->name('threat-intelligence');
    });
    
    // IP Management Routes (New Specialized Controller) - with stricter rate limiting
    Route::middleware('throttle:10,60')->prefix('ip-management')->name('ip-management.')->group(function () {
        // Blocked IPs
        Route::get('/blocked-ips', [IpManagementController::class, 'blockedIps'])->name('blocked-ips');
        Route::post('/block-ip', [IpManagementController::class, 'blockIp'])->name('block-ip');
        Route::post('/unblock-ip', [IpManagementController::class, 'unblockIp'])->name('unblock-ip');
        
        // Trusted IPs
        Route::get('/trusted-ips', [IpManagementController::class, 'trustedIps'])->name('trusted-ips');
        Route::post('/trust-ip', [IpManagementController::class, 'trustIp'])->name('trust-ip');
        Route::post('/untrust-ip', [IpManagementController::class, 'untrustIp'])->name('untrust-ip');
        
        // IP Details & Bulk Operations
        Route::get('/ip-details/{ip}', [IpManagementController::class, 'ipDetails'])->name('ip-details');
        Route::post('/bulk-operation', [IpManagementController::class, 'bulkOperation'])->name('bulk-operation');
    });
    
    // Legacy IP Management Routes (for backward compatibility)
    Route::middleware('throttle:10,60')->group(function () {
        Route::post('/legacy-block-ip', [SecurityLogController::class, 'blockIp'])->name('legacy.block-ip');
        Route::post('/legacy-trust-ip', [SecurityLogController::class, 'trustIp'])->name('legacy.trust-ip');
        Route::post('/legacy-unblock-ip', [SecurityLogController::class, 'unblockIp'])->name('legacy.unblock-ip');
        Route::post('/legacy-untrust-ip', [SecurityLogController::class, 'untrustIp'])->name('legacy.untrust-ip');
    });
    
    // Legacy IP Lists (redirect to new controllers)
    Route::get('/legacy-blocked-ips', [SecurityLogController::class, 'blockedIps'])->name('legacy.blocked-ips');
    Route::get('/legacy-trusted-ips', [SecurityLogController::class, 'trustedIps'])->name('legacy.trusted-ips');
    Route::get('/legacy-ip-details/{ip}', [SecurityLogController::class, 'ipDetails'])->name('legacy.ip-details');
    
    // Rate Limit Logs
    Route::get('/rate-limit-logs', [RateLimitLogController::class, 'index'])->name('rate-limit-logs.index');
    Route::delete('/rate-limit-logs/{log}', [RateLimitLogController::class, 'destroy'])->name('rate-limit-logs.destroy');
    Route::post('/rate-limit-logs/destroy-all', [RateLimitLogController::class, 'destroyAll'])->name('rate-limit-logs.destroy-all');
    Route::post('/rate-limit-logs/block-ip', [RateLimitLogController::class, 'blockIp'])
        ->middleware('throttle:10,60')
        ->name('rate-limit-logs.block-ip');
});
