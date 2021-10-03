<?php

namespace AdventDev\SuspiciousLogins\Console\Commands;

use AdventDev\SuspiciousLogins\Models\LoginAttempt;
use Illuminate\Console\Command;

class IPAddressHistory extends Command
{
    protected $signature = 'suspicious-logins:history {email}';
    protected $description = 'Recent login history for email';


    public function handle()
    {
        $email = $this->argument('email');
        $this->info('Checking recent logins for ' . $email);

        $logins = LoginAttempt::where('email', $email)->orderBy('created_at')->take(20)->get();
        foreach ($logins as $login) {
            $this->comment('  [' . $login->created_at . '] Login ' . $login->event . ' from ' . $login->ip . ' in ' . $login->geoip_country . ', ' . $login->geoip_city);
        }
    }
}
