<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Events\SecurityThreatDetected;
use App\Listeners\NotifySecurityTeam;
use App\Listeners\AutoThreatResponse;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Login::class => [
            'App\Listeners\UpdateUserStatus@handleLogin',
        ],
        Logout::class => [
            'App\Listeners\UpdateUserStatus@handleLogout',
        ],
        SecurityThreatDetected::class => [
            NotifySecurityTeam::class,
            AutoThreatResponse::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        // Register any additional events for your application.
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
