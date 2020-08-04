<?php

namespace FjordTest\Traits;

use Fjord\Fjord\Fjord;
use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use Mockery as m;

trait InteractsWithComponents
{
    public function setupApplication()
    {
        $this->app = new Application;
        $this->app['fjord'] = m::mock(Fjord::class);
        $components = m::mock(Fjord::class);
        $components->shouldReceive('isRegistered')->andReturn(false);
        $this->app['fjord']->shouldReceive('get')->andReturn($components);
        Container::setInstance($this->app);
    }
}
