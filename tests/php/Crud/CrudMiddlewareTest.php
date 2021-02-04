<?php

namespace Tests\Crud;

use Ignite\Crud\Config\CrudConfig;
use Ignite\Crud\CrudMiddleware;
use Illuminate\Http\Request;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class CrudMiddlewareTest extends TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_calls_setModelInstanceFromCurrentRoute_on_crud_config()
    {
        $config = m::mock('config');
        $config->shouldReceive('is')->withArgs([CrudConfig::class])->once()->andReturn(true);
        $config->shouldReceive('setModelInstanceFromCurrentRoute')->once();

        $route = m::mock('route');
        $route->shouldReceive('getConfig')->once()->andReturn($config);

        $request = m::mock(Request::class);
        $request->shouldReceive('route')->once()->andReturn($route);

        (new CrudMiddleware)->handle($request, fn ($r) => null);
    }
}
