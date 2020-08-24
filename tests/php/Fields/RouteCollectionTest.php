<?php

namespace Tests\Fields;

use Lit\Crud\Fields\Route\RouteCollection;
use Lit\Crud\Fields\Route\RouteItem;
use Tests\BackendTestCase;

class RouteCollectionTest extends BackendTestCase
{
    /** @test */
    public function test_Route_method_adds_RouteItem()
    {
        $collection = new RouteCollection([]);
        $collection->route('', '', fn () => null);
        $this->assertInstanceOf(RouteItem::class, $collection->first());
    }

    /** @test */
    public function test_group_adds_new_route_collection()
    {
        $collection = new RouteCollection([]);
        $collection->group('', '', fn ($group) => $this->assertNotEquals($collection, $group));
        $this->assertInstanceOf(RouteCollection::class, $collection->first());
    }

    /** @test */
    public function test_findRoute_method()
    {
        $collection = new RouteCollection([]);
        $collection->route('', 'dummy_id', fn () => null);
        $this->assertEquals($collection->first(), $collection->findRoute('dummy_id'));
    }
}
