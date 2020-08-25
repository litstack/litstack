<?php

namespace LitPermissions;

use Ignite\Application\Package\LitPackage;

class Package extends LitPackage
{
    /**
     * List of service providers to be registered for this package.
     *
     * @var array
     */
    protected $providers = [
        \LitPermissions\ServiceProvider::class,
    ];

    /**
     * List of components this package contains.
     *
     * @var array
     */
    protected $components = [
        // 'lit-permissions' => PermissionsComponent::class
    ];

    /**
     * List of handlers for config files.
     *
     * @var array
     */
    protected $configHandler = [];
}
