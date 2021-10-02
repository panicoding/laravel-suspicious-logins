<?php

namespace AdventDev\SuspiciousLogins\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    public const FAILED = 'failed';
    public const SUCCEEDED = 'succeeded';

    protected $fillable = [
        'event', 'user_id', 'email', 'ip', 'geoip_city', 'geoip_country', 'geoip_lon', 'geoip_lat', 'geoip_state'
    ];
}
