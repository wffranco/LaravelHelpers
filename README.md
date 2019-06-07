Package that contains some helpers for Laravel 5.*

- [Installation](#installation)
    - [Composer](#composer)
    - [Cors](#composer)
        - [Service Provider](#service-provider)
        - [Config File](#config-file)
        - [Cors Middleware](#cors-middleware)
- [Usage](#usage)

# Installation

This package is easy to set up. Just follow a couple of steps.

## Composer

Pull this package in through Composer (file `composer.json`).

```js
{
    "require": {
        "wffranco/laravel-helpers": "~1.0"
    }
}
```

## Cors

If you need to use cross origin, here provides a solution.

### Service Provider

Add the package to your application service providers in `config/app.php` file.

```php
'providers' => [
    ...
    /**
     * Third Party Service Providers...
     */
    Wffranco\Helpers\ServiceProvider::class,
],
```

### Config File

Publish the package config file to your application. Run these command inside your terminal.
```bash
php artisan vendor:publish --provider="Wffranco\Helpers\ServiceProvider" --tag=config
```
Change the configuration acording to your needs.

### Cors Middleware

Finally, add the Cors middleware to your `app\Http\Kernel.php` file.

```php
protected $middleware = [
    ...
    \Wffranco\Helpers\Http\Middleware\Cors::class,
];
```

# Usage

Documentation not finished.
