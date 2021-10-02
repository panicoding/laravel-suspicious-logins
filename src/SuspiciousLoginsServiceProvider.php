<?php

namespace AdventDev\SuspiciousLogins;

use AdventDev\SuspiciousLogins\Console\Commands\LookupIPAddress;
use Illuminate\Support\ServiceProvider;
use AdventDev\SuspiciousLogins\Console\Commands\ClearLoginAttempts;

class SuspiciousLoginsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerFiles();
    }

    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ClearLoginAttempts::class,
                LookupIPAddress::class,
            ]);
        }
    }

    protected function registerFiles(): void
    {
        $this->publishes([
            __DIR__ . '/../config/suspicious-logins.php' => config_path('suspicious-logins.php'),
        ], 'config');

        if (! class_exists('CreateLoginAttemptsTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_login_attempts_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_login_attempts_table.php'),
            ], 'migrations');
        }
    }
}
