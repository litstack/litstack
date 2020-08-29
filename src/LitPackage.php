<?php

namespace Ignite;

use Ignite\Application\Navigation\Config as NavigationConfig;
use Ignite\Application\Navigation\NavigationConfigFactory;
use Ignite\Application\Package\LitPackage as Package;
use Ignite\Crud\Config\Factories\CrudFormConfigFactory;
use Ignite\Crud\Config\Factories\CrudIndexConfigFactory;
use Ignite\Crud\Config\Traits\HasCrudIndex;
use Ignite\Crud\Config\Traits\HasCrudShow;

class LitPackage extends Package
{
    /**
     * List of config classes with their associated factories.
     *
     * @var array
     */
    protected $configFactories = [
        // Navigation
        NavigationConfig::class => NavigationConfigFactory::class,

        // Crud
        HasCrudShow::class  => CrudFormConfigFactory::class,
        HasCrudIndex::class => CrudIndexConfigFactory::class,
    ];
}
