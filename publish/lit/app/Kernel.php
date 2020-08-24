<?php

namespace LitApp;

use Lit\Application\Kernel as LitKernel;

class Kernel extends LitKernel
{
    /**
     * The Lit extension provided by your application.
     *
     * @var array
     */
    public $extensions = [];

    /**
     * Lit application service providers.
     *
     * @var array
     */
    public $providers = [
        Providers\LocalizationServiceProvider::class,
        Providers\LitServiceProvider::class,
    ];
}
