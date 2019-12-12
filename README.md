Package that contains some helpers for Laravel 5.*

- [Installation](#installation)
    - [Composer](#composer)
    - [Service Provider](#service-provider)
- [Usage](#usage)

## Installation

Still in develop, but this package is easy to set up. Just follow a couple of steps.

### Composer

Pull this package in through Composer (file `composer.json`).

```js
{
    "require": {
        "wffranco/laravel-helpers": "~1.0"
    }
}
```

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

## Usage

Documentation not ready.
