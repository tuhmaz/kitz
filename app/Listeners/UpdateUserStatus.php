<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Models\VisitorTracking;
use Carbon\Carbon;
use App\Models\User; // imported for phpdoc recognition

class UpdateUserStatus
{
    public function handleLogin(Login $event)
    {
        $user = $event->user;
        /** @var User $user */
        $user->status = 'online';
        $user->last_seen = now();
        $user->save();

        // تحديث سجل الزيارة
        VisitorTracking::updateOrCreate(
            ['user_id' => $user->id],
            [
                'ip_address'    => request()->ip() ?? '127.0.0.1',
                'user_agent'    => request()->userAgent(),
                'last_activity' => now()
            ]
        );
    }

    public function handleLogout(Logout $event)
    {
        $user = $event->user;
        /** @var User|null $user */
        if ($user) {
            $user->status = 'offline';
            $user->last_seen = now();
            $user->save();
        }
    }
}