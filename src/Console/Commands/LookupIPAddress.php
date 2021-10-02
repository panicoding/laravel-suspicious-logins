<?php

namespace AdventDev\SuspiciousLogins\Console\Commands;

use Illuminate\Console\Command;
use AdventDev\SuspiciousLogins\Models\LoginAttempt;

class LookupIPAddress extends Command
{
    protected $signature = 'suspicious-logins:lookup {ip}';
    protected $description = 'Test getting data for an IP';


    public function handle()
    {
        $ip = $this->argument('ip');
        $this->info('Looking up ' . $ip);

        $geoip = geoip()->getLocation($ip);
        foreach ($geoip->toArray() as $key => $val) {
            $this->comment(" o " . $key . ': ' . $val);
        }
    }
}
