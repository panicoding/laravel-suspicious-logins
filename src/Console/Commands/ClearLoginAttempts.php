<?php

namespace AdventDev\SuspiciousLogins\Console\Commands;

use Illuminate\Console\Command;
use AdventDev\SuspiciousLogins\Models\LoginAttempt;

class ClearLoginAttempts extends Command
{
    protected $signature = 'suspicious-logins:clear';
    protected $description = 'Clear all login attempts in the database';


    public function handle()
    {
        $this->info('Deleting LoginAttempt History...');

        LoginAttempt::delete();

        $this->info('Completed.');
    }
}
