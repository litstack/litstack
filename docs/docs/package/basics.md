# Packages

The package offers not only an interface to manage and administer data, users and permissions, but also the possibility to integrate packages into the system. When creating the packages, the developer is free to decide what the packages should be able to do.

Packages can offer the possibility to be extended by other packages, or to intervene in existing packages.

## Setup

For the package to be able to discover your package it must be specified in the composer.json as follows:

```json
{
    ...
    "extra": {
        "fjord": "MyPackage\\Package"
    }
}
```

Here we come to the creation of the `Package` class. It contains the main components of the package such as the **service providers**, **Artisan commands** and many more. All components specified in the `Package` class are automatically registered.

```php
<?php

namespace MyPackage;

use Fjord\Application\Package\FjordPackage;

class Package extends FjordPackage
{
    /**
     * List of service providers to be registered for this package.
     *
     * @var array
     */
    protected $providers = [
        \MyPackage\ServiceProvider::class,
    ];
}
```

## Routing

All routes for fjord packages are defined in a RouteServiceProvider.

```php
namespace MyPackage;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        //
    }
}
```
