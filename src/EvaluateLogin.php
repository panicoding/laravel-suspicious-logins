<?php

namespace AdventDev\SuspiciousLogins;

use AdventDev\SuspiciousLogins\Models\LoginAttempt;

class EvaluateLogin
{
    public static function success(LoginAttempt $current, $recentLogins)
    {
        foreach ($recentLogins as $login) {
            // Identical IP is safe
            if ($login->ip === $current->ip) {
                return true;
            }

            // @TODO add bot detection here

            // Same city is safe
            if (config('suspicious-logins.safe.city', true) && $login->geoip_city === $current->geoip_city) {
                return true;
            }

            // Same country is safe
            if (config('suspicious-logins.safe.country', false) && $login->geoip_country === $current->geoip_country) {
                return true;
            }
        }

        // By default it is not safe
        return false;
    }
}
