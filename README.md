# Laravel Suspicious Logins

Detect suspicious logins for standard Laravel authentication (base Laravel, Jetstream, etc) and notify a list 
of administrators and/or the user of the login. 

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
```

## Use

Add the ```AdventDev\SuspiciousLogins\Listeners\AuthEventSubscriber``` to the ```$subscribe``` variable in the ```app/Providers/EventServiceProvider.php``` file.

```php
protected $subscribe = [
    'AdventDev\SuspiciousLogins\Listeners\AuthEventSubscriber',
];
```

Make sure to update config/suspicious-logins.php with your preferences.

Depending on your config file it will now email you, and/or your users when a suspicious login occurs on their
account. By default that is a login from another city than they have recently logged in from.


### Commands

Clear all login attempts in the database
```bash
php artisan suspicious-logins:clear
```

Test a GeoIP lookup for {ip}
```bash
php artisan suspicious-logins:lookup {ip}
```

Prune any logins older than 30 days. We advise you add this to a daily schedule.
```bash
php artisan suspicious-logins:prune
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

Licensed under the MIT license. Please see [License File](LICENSE.md) for more information.
