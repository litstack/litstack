<?php

namespace FjordTest\Fields;

use Fjord\Crud\Casts\Route;
use FjordTest\BackendTestCase;
use Mockery as m;

class RouteCastTest extends BackendTestCase
{
    /** @test */
    public function test_get_method()
    {
        $resolver = m::mock('resolver');
        $collection = m::mock('route_collection');
        $resolver->shouldReceive('resolve')->withArgs(['main'])->andReturn($collection);
        $collection->shouldReceive('findRoute')->withArgs(['test'])->andReturn('result');
        app()->bind('fjord.app.crud.route.resolver', fn () => $resolver);
        $cast = new Route;
        $result = $cast->get(null, '', 'main.test', []);
        $this->assertEquals('result', $result);
    }

    /** @test */
    public function test_get_method_returns_null_when_route_collection_cannot_be_resolved()
    {
        $collection = m::mock('route_collection');
        $collection->shouldReceive('findRoute')->withArgs(['test'])->andReturn('result');
        $cast = new Route;
        $result = $cast->get(null, '', 'main.test', []);
        $this->assertEquals(null, $result);
    }
}
