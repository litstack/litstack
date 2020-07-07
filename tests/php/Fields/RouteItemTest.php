<?php

namespace FjordTest\Fields;

use Fjord\Crud\Fields\Route\RouteCollection;
use Fjord\Crud\Fields\Route\RouteItem;
use Fjord\Fjord\Fjord;
use FjordTest\BackendTestCase;
use Mockery as m;

class RouteItemTest extends BackendTestCase
{
    /** @test */
    public function test_getTitle_method()
    {
        $item = new RouteItem('dummy title', '', fn () => null);
        $this->assertEquals('dummy title', $item->getTitle());
    }

    /** @test */
    public function test_getId_concats_id_to_collection_id()
    {
        $collection = m::mock(RouteCollection::class);
        $collection->shouldReceive('getId')->andReturn('collection');
        $item = new RouteItem('', 'dummy_id', fn () => null, $collection);
        $this->assertEquals('collection.dummy_id', $item->toArray());
    }

    /** @test */
    public function test_toArray_returns_id()
    {
        $item = new RouteItem('', 'dummy_id', fn () => null);
        $this->assertEquals('dummy_id', $item->toArray());
    }

    /** @test */
    public function test_toJson_returns_id()
    {
        $item = new RouteItem('', 'dummy_id', fn () => null);
        $this->assertEquals('"dummy_id"', $item->toJson());
    }

    /** @test */
    public function test_toString_returns_resolved()
    {
        $item = new RouteItem('', '', fn () => '/dummy-url');
        $this->assertEquals('/dummy-url', (string) $item);
    }

    /** @test */
    public function test_resolver_receives_locale()
    {
        $fjord = m::mock(Fjord::class);
        $fjord->shouldReceive('getLocale')->andReturn('de');
        app()->bind('fjord', fn () => $fjord);
        $item = new RouteItem('', '', function (...$parameters) {
            $this->assertNotEmpty($parameters);
            $this->assertEquals('de', $parameters[0]);
        });
        $item->resolve();
    }
}
