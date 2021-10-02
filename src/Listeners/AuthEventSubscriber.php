<?php

namespace AdventDev\SuspiciousLogins\Listeners;

use AdventDev\SuspiciousLogins\EvaluateLogin;
use AdventDev\SuspiciousLogins\Models\LoginAttempt;
use AdventDev\SuspiciousLogins\Notifications\SuspiciousLoginDetected;
use Carbon\Carbon;
use Notification;
use Torann\GeoIP\Facades\GeoIP;

class AuthEventSubscriber
{
    public function recordSuccessfulLogin($event)
    {
        if (!config('suspicious-logins.record_successful_login')) {
            return;
        }

        $recentLogins = LoginAttempt::whereEvent(LoginAttempt::SUCCEEDED)
            ->whereUserId($event->user->id)
            ->whereEmail($event->user[config('suspicious-logins.email_column')])
            ->whereIp(request()->ip())
            ->orderByDesc('created_at')
            ->where('created_at', '>', Carbon::now()->subMonth())
            ->get();

        $geoIp = geoip()->getLocation(request()->ip());

        $current = LoginAttempt::create([
            'event' => LoginAttempt::SUCCEEDED,
            'user_id' => $event->user->id,
            'email' => $event->user[config('suspicious-logins.email_column')],

            'ip' => request()->ip(),

            'geoip_city' => $geoIp->city,
            'geoip_country' => $geoIp->country,
            'geoip_state' => $geoIp->state_name,
            'geoip_lat' => $geoIp->lat,
            'geoip_lon' => $geoIp->lon,
        ]);

        // If we have no recent logins we cannot make any decisions
        if ($recentLogins->count() === 0) {
            return;
        }

        $safe = EvaluateLogin::success($current, $recentLogins);

        if (!$safe) {
            // Tell admins about it
            $admins = config('suspicious-logins.notify_admins');
            foreach ($admins as $email) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    continue;
                }

                Notification::route('mail', $email)->notify(
                    new SuspiciousLoginDetected($event->user, $geoIp, request()->ip())
                );
            }

            // Tell the user about it
            if (config('suspicious-logins.notify_users')) {
                Notification::route('mail', $event->user[config('suspicious-logins.email_column')])->notify(
                    new SuspiciousLoginDetected($event->user, $geoIp, request()->ip())
                );
            }
        }
    }

    /**
     * Handle recordFailedLogin.
     */
    public function recordFailedLogin($event)
    {
        if (!config('suspicious-logins.record_failed_login')) {
            return;
        }

        $geoIp = geoip()->getLocation(request()->ip());

        LoginAttempt::create([
            'event' => LoginAttempt::FAILED,
            'user_id' => null,
            'email' => $event->credentials['email'],
            'ip' => request()->ip(),

            'geoip_city' => $geoIp->city,
            'geoip_country' => $geoIp->country,
            'geoip_state' => $geoIp->state_name,
            'geoip_lat' => $geoIp->lat,
            'geoip_lon' => $geoIp->lon,
        ]);
    }

    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Validated',
            'AdventDev\SuspiciousLogins\Listeners\AuthEventSubscriber@recordSuccessfulLogin'
        );

        $events->listen(
            'Illuminate\Auth\Events\Failed',
            'AdventDev\SuspiciousLogins\Listeners\AuthEventSubscriber@recordFailedLogin'
        );
    }
}
