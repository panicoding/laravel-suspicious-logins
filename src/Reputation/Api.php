<?php

namespace AdventDev\SuspiciousLogins\Reputation;

use AdventDev\SuspiciousLogins\Reputation\Exceptions\FailedToCheck;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Api
{
    private $ip;
    private $url = 'https://reputation.advent.dev/api';
    private $cacheKey = 'adventdev-reputation';
    private $cacheTime = 300;

    public function __construct(string $ip)
    {
        $this->ip = $ip;
    }

    public function check()
    {
        try {
            $ip = $this->ip;
            return Cache::remember($this->cacheKey . '.' . md5($this->ip), $this->cacheTime, function() use ($ip) {
                return Http::acceptJson()->timeout(5)->get($this->url . '/check/' . $ip)->json();
            });
        } catch (\Exception $ex) {
            throw new FailedToCheck($ex->getMessage());
        }
    }

    public function success()
    {
        try {
            Http::acceptJson()->timeout(5)->post($this->url . '/submit', [
                'ip' => $this->ip,
                'action' => 'success',
            ]);
        } catch (\Exception $ex) {
            Log::error('Could not submit success to reputation service: ' . $ex->getMessage());
        }
    }

    public function fail()
    {
        try {
            Http::acceptJson()->timeout(5)->post($this->url . '/submit', [
                'ip' => $this->ip,
                'action' => 'fail',
            ]);
        } catch (\Exception $ex) {
            Log::error('Could not submit success to reputation service: ' . $ex->getMessage());
        }
    }
}
