<?php

namespace AdventDev\SuspiciousLogins\Console\Commands;

use AdventDev\SuspiciousLogins\Reputation\Api;
use Illuminate\Console\Command;

class LookupIPAddress extends Command
{
    protected $signature = 'suspicious-logins:lookup {ip}';
    protected $description = 'Test getting data for an IP';


    public function handle()
    {
        $ip = $this->argument('ip');
        $this->info('Looking up GeoIP info for ' . $ip);

        $geoip = geoip()->getLocation($ip);
        foreach ($geoip->toArray() as $key => $val) {
            $this->comment(" o " . $key . ': ' . $val);
        }

        $this->newLine();

        $this->info('Looking up Advent Reputation info for ' . $ip);
        $api = new Api($ip);
        $info = $api->check();
        foreach ($info as $key => $val) {
            $this->comment(" o " . $key . ': ' . $val);
        }

    }
}
