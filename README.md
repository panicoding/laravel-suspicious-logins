<p align="center"><img src="/docs/social.png" alt="Laravel Suspicious Logins"></p>

# Laravel Suspicious Logins

[![Latest Version on Packagist](https://img.shields.io/packagist/v/adventdevinc/laravel-suspicious-logins.svg?style=flat-square)](https://packagist.org/packages/adventdevinc/laravel-suspicious-logins)
[![Total Downloads](https://img.shields.io/packagist/dt/adventdevinc/laravel-suspicious-logins)](https://packagist.org/packages/adventdevinc/laravel-suspicious-logins)
![License](https://img.shields.io/github/license/adventdevinc/laravel-suspicious-logins)

---

Detect suspicious logins for standard Laravel authentication (base Laravel, Jetstream, etc) and notify a list 
of administrators and/or the user of the login automaticall via email. 

Also provides (optional) integration with Advent Reputation for checking the reputation of IP addresses.

## Install

via composer

``` bash
$ composer require adventdevinc/laravel-suspicious-logins
```

Now you need to publish the database migration, and run migrate to apply it. 
```bash
php artisan vendor:publish --provider="AdventDev\SuspiciousLogins\SuspiciousLoginsServiceProvider" --tag="migrations"
php artisan migrate
```

Publish the suspicious-logins.php config file and then edit it (config/suspicious-logins.php) to set your 
preferences.

```bash
php artisan vendor:publish --provider="AdventDev\SuspiciousLogins\SuspiciousLoginsServiceProvider" --tag="config"
php artisan vendor:publish --provider="Torann\GeoIP\GeoIPServiceProvider" --tag="config"
```

## Use

Add ```\AdventDev\SuspiciousLogins\Listeners\AuthEventSubscriber::class,``` to the ```$subscribe``` variable in the ```app/Providers/EventServiceProvider.php``` file. 
If it does not exist just add the code below.

```php
protected $subscribe = [
    \AdventDev\SuspiciousLogins\Listeners\AuthEventSubscriber::class,
];
```

Make sure to update config/suspicious-logins.php with your preferences.

Depending on your config file it will now email you, and/or your users when a suspicious login occurs on their
account. By default that is a login from another city than they have recently logged in from.



## Example Email
<img src="/docs/example-email.png" alt="Example Email">



## Commands

Clear all login attempts in the database
```bash
php artisan suspicious-logins:clear
```

Test a GeoIP lookup and Advent Reputation response for {ip} 
```bash
php artisan suspicious-logins:lookup {ip}
```

Prune any logins older than 30 days. We automatically add this to your daily schedule.
```bash
php artisan suspicious-logins:prune
```


## Reputation Service
This package includes support for a central IP reputation service that uses OSINT and machine learning to 
predict suspicious logins. 

By default, it is disabled but can be enabled by changing ```reputation.enabled``` to be true. Opting in to 
this service will check logins against the database by querying only the IP address logging in, and 
will submit actual logins and their status (failed or success) with the IP to the service 
to help train it.

Learn more at [https://reputation.advent.dev](https://reputation.advent.dev).

NOTE: This is completely optional to use, if you disable it country, distance and city 
checking will still work!

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## GeoIP Inclusion & Licensing

This package includes [torann/laravel-geoip](https://github.com/Torann/laravel-geoip) for GeoIP lookups 
which supports several options for GeoIP lookups. 

The default allows free use for non-commercial purposes. You can publish the laravel-geoip config 
to change your default and use a different database. There are some that are free for commercial uses, 
or you can buy a key for ip-api from ~$13/mo.

The fees or licenses required are not related to this project, and it will work with virtually any 
IP lookup database you have.

## License

Licensed under the MIT license. Please see [License File](LICENSE.md) for more information.
