<?php

namespace AdventDev\SuspiciousLogins\Reputation;

use AdventDev\SuspiciousLogins\Reputation\Exceptions\FailedToCheck;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

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
                return Http::acceptJson()->get($this->url . '/check/' . $ip)->json();
            });
        } catch (\Exception $ex) {
            throw new FailedToCheck($ex->getMessage());
        }
    }

    public function success()
    {
        Http::acceptJson()->async()->post($this->url . '/submit', [
            'ip' => $this->ip,
            'action' => 'success',
        ]);
    }

    public function fail()
    {
        Http::acceptJson()->async()->post($this->url . '/submit', [
            'ip' => $this->ip,
            'action' => 'fail',
        ]);
    }
}
