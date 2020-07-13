# Package Development

Packages can extend the functionality and the admin interface. You can use them to integrate your own Vue components into the application or extend existing components.

## Setup

For the application to be able to discover your package it must be specified in the composer.json as follows:

```json
{
    ...
    "extra": {
        "fjord": "MyPackage\\Package"
    }
}
```

Here we come to the creation of the `Package` class. It contains the main components of the package such as the service providers.

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

    /**
     * List of components this package contains.
     *
     * @var array
     */
    protected $components = [
        ...
    ];

    /**
     * List of handlers for config files.
     *
     * @var array
     */
    protected $configFiles = [
        ...
    ];
}
```
