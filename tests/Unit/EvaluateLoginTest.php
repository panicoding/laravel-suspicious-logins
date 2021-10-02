<?php

test('false with no data', function () {
    $current = new \AdventDev\SuspiciousLogins\Models\LoginAttempt();
    $result = \AdventDev\SuspiciousLogins\EvaluateLogin::success($current, []);

    expect($result)->toBeFalse();
});


test('true with matching ips', function () {
    $current = new \AdventDev\SuspiciousLogins\Models\LoginAttempt();
    $current->ip = '1.2.3.4';

    $result = \AdventDev\SuspiciousLogins\EvaluateLogin::success($current, [$current]);

    expect($result)->toBeTrue();
});


test('true with matching cities', function () {
    $current = new \AdventDev\SuspiciousLogins\Models\LoginAttempt();
    $current->ip = '1.2.3.4';
    $current->geoip_city = 'Hoth';

    $old = new \AdventDev\SuspiciousLogins\Models\LoginAttempt();
    $old->ip = '5.6.7.8';
    $old->geoip_city = 'Hoth';

    $result = \AdventDev\SuspiciousLogins\EvaluateLogin::success($current, [$old]);

    expect($result)->toBeTrue();
});


test('false with different ips and cities', function () {
    $current = new \AdventDev\SuspiciousLogins\Models\LoginAttempt();
    $current->ip = '1.2.3.4';
    $current->geoip_city = 'Hoth';
    $current->geoip_country = 'Advent';

    $old = new \AdventDev\SuspiciousLogins\Models\LoginAttempt();
    $old->ip = '5.6.7.8';
    $old->geoip_city = 'NotHoth';
    $old->geoip_country = 'Advent';

    $result = \AdventDev\SuspiciousLogins\EvaluateLogin::success($current, [$old]);

    expect($result)->toBeFalse();
});

