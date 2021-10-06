<?php

namespace AdventDev\SuspiciousLogins\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use AdventDev\SuspiciousLogins\Models\LoginAttempt;

class PruneLoginAttempts extends Command
{
    protected $signature = 'suspicious-logins:prune';
    protected $description = 'Remove old data from the login attempts tables';


    public function handle()
    {
        $this->info('Pruning old records...');

        LoginAttempt::where('created_at', '<', Carbon::now()->subMonth())->delete();

        $this->info('Completed.');
    }
}
