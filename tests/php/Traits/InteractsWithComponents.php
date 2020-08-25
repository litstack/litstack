<?php

namespace Tests\Traits;

use Ignite\Lit\Lit;
use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use Mockery as m;

trait InteractsWithComponents
{
    public function setupApplication()
    {
        $this->app = new Application;
        $this->app['lit'] = m::mock(Lit::class);
        $components = m::mock(Lit::class);
        $components->shouldReceive('isRegistered')->andReturn(false);
        $this->app['lit']->shouldReceive('get')->andReturn($components);
        Container::setInstance($this->app);
    }
}
