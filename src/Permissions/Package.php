<?php

namespace FjordPermissions;

use Fjord\Application\Package\FjordPackage;

class Package extends FjordPackage
{
    /**
     * List of service providers to be registered for this package.
     *
     * @var array
     */
    protected $providers = [
        \FjordPermissions\ServiceProvider::class,
    ];

    /**
     * List of components this package contains.
     *
     * @var array
     */
    protected $components = [
        // 'fj-permissions' => PermissionsComponent::class
    ];

    /**
     * List of handlers for config files.
     *
     * @var array
     */
    protected $configHandler = [];
}
