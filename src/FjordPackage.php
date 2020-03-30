<?php

namespace AwStudio\Fjord;

use AwStudio\Fjord\User\Components\UsersComponent;
use AwStudio\Fjord\Application\Package\FjordPackage as Package;

class FjordPackage extends Package
{
    /**
     * List of service providers to be registered for this package.
     * 
     * @var array
     */
    protected $providers = [
        \AwStudio\Fjord\User\ServiceProvider::class,
        \AwStudio\Fjord\Form\ServiceProvider::class
    ];

    /**
     * List of components this package contains.
     * 
     * @var array
     */
    protected $components = [
        'fj-users' => UsersComponent::class
    ];

    /**
     * List of handlers for config files.
     * 
     * @var array
     */
    protected $configFiles = [
        'users.table' => \AwStudio\Fjord\User\Config\TableConfig::class
    ];
}