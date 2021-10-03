<?php

namespace AdventDev\SuspiciousLogins;

use AdventDev\SuspiciousLogins\Models\LoginAttempt;

class EvaluateLogin
{
    public static function success(LoginAttempt $current, $recentLogins): bool
    {
        // Loop through the most recent logins to look for matches
        foreach ($recentLogins as $login) {
            // Identical IP is safe
            if ($login->ip === $current->ip) {
                return true;
            }

            // Same city is safe
            if (config('suspicious-logins.safe.city', true) && $login->geoip_city === $current->geoip_city) {
                return true;
            }

            // Same country is safe
            if (config('suspicious-logins.safe.country', false) && $login->geoip_country === $current->geoip_country) {
                return true;
            }

            // Within 250km aka 155 miles
            if (self::distance($current->geoip_lat, $current->geoip_lon, $login->geoip_lat, $login->geoip_lon) <= 250) {
                return true;
            }
        }

        // By default, it is not safe
        return false;
    }

    /**
     * Calculate the distance between two lat|lon pairs
     */
    private static function distance($latitude1, $longitude1, $latitude2, $longitude2): float
    {
        $theta = $longitude1 - $longitude2;
        $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance *= 60 * 1.1515;
        $distance *= 1.609344;

        return (round($distance,2));
    }
}
