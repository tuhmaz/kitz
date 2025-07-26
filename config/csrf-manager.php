<?php

return [
    /*
    |--------------------------------------------------------------------------
    | CSRF Manager Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the CSRF Manager JavaScript
    | component that handles automatic CSRF token refresh and session management.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | When enabled, the CSRF Manager will output debug messages to the browser
    | console. This is useful for development but should be disabled in production.
    |
    */
    'debug' => env('CSRF_DEBUG', config('app.debug')),

    /*
    |--------------------------------------------------------------------------
    | Token Refresh Interval
    |--------------------------------------------------------------------------
    |
    | How often (in milliseconds) to refresh the CSRF token automatically.
    | Default is 5 minutes (300000 ms).
    |
    */
    'refresh_interval' => env('CSRF_REFRESH_INTERVAL', 5 * 60 * 1000),

    /*
    |--------------------------------------------------------------------------
    | Inactivity Warning Time
    |--------------------------------------------------------------------------
    |
    | How long before session expiry (in milliseconds) to show a warning to the user.
    | Default is 2 minutes (120000 ms).
    |
    */
    'warning_time' => env('CSRF_WARNING_TIME', 2 * 60 * 1000),

    /*
    |--------------------------------------------------------------------------
    | Maximum Inactivity Time
    |--------------------------------------------------------------------------
    |
    | Maximum time of user inactivity (in milliseconds) before showing session
    | expiry warning. Default is 30 minutes (1800000 ms).
    |
    */
    'max_inactivity' => env('CSRF_MAX_INACTIVITY', 30 * 60 * 1000),

    /*
    |--------------------------------------------------------------------------
    | CSRF Endpoints
    |--------------------------------------------------------------------------
    |
    | URLs for CSRF token refresh and validation endpoints.
    |
    */
    'endpoints' => [
        'refresh' => '/csrf-refresh',
        'check' => '/csrf-check',
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto Start
    |--------------------------------------------------------------------------
    |
    | Whether to automatically start the CSRF Manager when the page loads.
    |
    */
    'auto_start' => env('CSRF_AUTO_START', true),

    /*
    |--------------------------------------------------------------------------
    | Console Logging
    |--------------------------------------------------------------------------
    |
    | Control what types of messages are logged to the console.
    |
    */
    'logging' => [
        'enabled' => env('CSRF_LOGGING', config('app.debug')),
        'levels' => [
            'info' => env('CSRF_LOG_INFO', true),
            'warning' => env('CSRF_LOG_WARNING', true),
            'error' => env('CSRF_LOG_ERROR', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Activity Tracking
    |--------------------------------------------------------------------------
    |
    | Events to track for user activity detection.
    |
    */
    'activity_events' => [
        'mousedown',
        'mousemove', 
        'keypress',
        'scroll',
        'touchstart',
        'click'
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Handling
    |--------------------------------------------------------------------------
    |
    | Configuration for error handling and retry logic.
    |
    */
    'error_handling' => [
        'retry_attempts' => env('CSRF_RETRY_ATTEMPTS', 3),
        'retry_delay' => env('CSRF_RETRY_DELAY', 5000), // 5 seconds
        'show_user_errors' => env('CSRF_SHOW_USER_ERRORS', true),
    ],
];
