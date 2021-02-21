<?php

namespace Tests\Traits;

use Ignite\Vue\Vue;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Facade;

trait InteractsWithComponents
{
    public function setupApplication()
    {
        $this->app = new Application;
        $this->app->singleton('lit.vue', fn () => new Vue);
        Facade::setFacadeApplication($this->app);
    }
}
