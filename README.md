# Passport Module Auth

This package provides model, commands & middleware to easily forbid access to certain modules(domains in DDD) when using Client Credentials Grant Tokens.

## Installation

You can install the package via composer:

```bash
composer require grixu/passport-module-auth
```

## Usage

Publish config file to adjust module names in your project:
```shell script
php artisan vendor:publish --tag=config --provider=Grixu\\PassportModuleAuth\\PassportModuleAuthServiceProvider
```

Add the middleware to your Kernel class which handling HTTP requests in your Laravel project. In default project scaffold you should edit: `app/Http/Kernel.php`

```php
use Grixu\PassportModuleAuth\Middleware\ModuleAuthMiddleware;

/**
 * The application's route middleware.
 *
 * These middleware may be assigned to groups or used individually.
 *
 * @var array
 */
protected $routeMiddleware = [
    'auth' => Authenticate::class,
    'auth.basic' => AuthenticateWithBasicAuth::class,
    'client' => CheckClientCredentials::class,
    'cache.headers' => SetCacheHeaders::class,
    'can' => Authorize::class,
    'guest' => RedirectIfAuthenticated::class,
    'password.confirm' => RequirePassword::class,
    'signed' => ValidateSignature::class,
    'throttle' => ThrottleRequests::class,
    'verified' => EnsureEmailIsVerified::class,
    'passport_module' => ModuleAuthMiddleware::class
];
```

Then just use it in your controllers or routes. Remember about passing module name!
```php
Route::middleware(['passport_module:products']);
```

### Artisan command

You can use artisan command `passport:module-auth` to manage entries in Module Auth system.

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email mateusz.gostanski@gmail.com instead of using the issue tracker.

## Credits

- [grixu](https://github.com/grixu)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
