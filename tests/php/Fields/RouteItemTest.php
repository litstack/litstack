<?php

namespace Tests\Fields;

use Ignite\Crud\Fields\Route\RouteCollection;
use Ignite\Crud\Fields\Route\RouteItem;
use Illuminate\Http\Request;
use Mockery as m;
use Tests\BackendTestCase;

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
        app()->setLocale('dummy-locale');
        $item = new RouteItem('', '', function (...$parameters) {
            $this->assertNotEmpty($parameters);
            $this->assertEquals('dummy-locale', $parameters[0]);
        });
        $item->resolve();
    }

    /** @test */
    public function test_route_method_returns_resolved()
    {
        $item = new RouteItem('', '', fn () => '/dummy-url');
        $this->assertEquals('/dummy-url', $item->route());
    }

    /** @test */
    public function test_route_method_returns_default()
    {
        $item = new RouteItem('', '', fn () => null);
        $this->assertEquals('/', $item->route());
    }

    /** @test */
    public function test_trimmed_method()
    {
        $item = new RouteItem('', '', fn () => '/dummy/route');
        $this->assertEquals('dummy/route', $item->trimmed());
    }

    /** @test */
    public function test_trimmed_method_trims_app_url()
    {
        app('config')->set('app.url', 'https://www.lit-admin.com');
        $item = new RouteItem('', '', fn () => 'https://www.lit-admin.com/dummy/route');
        $this->assertEquals('dummy/route', $item->trimmed());
    }

    /** @test */
    public function test_decodeRoute_method()
    {
        $item = new RouteItem('', '', fn () => '/dummy/%20');
        $this->assertEquals('/dummy/ ', $item->decodeRoute());
    }

    /** @test */
    public function test_decodeRoute_method_trimmed()
    {
        $item = new RouteItem('', '', fn () => '/dummy/%20');
        $this->assertEquals('dummy/ ', $item->decodeRoute(true));
    }

    /** @test */
    public function test_isActive_method()
    {
        $request = m::mock(Request::class);
        app()->bind('request', fn () => $request);

        $request->shouldReceive('is')->withArgs(['dummy/route*'])->once()->andReturn(true);
        $request->shouldReceive('is')->withArgs(['dummy/route*'])->once()->andReturn(false);
        $item = new RouteItem('', '', fn () => '/dummy/route');
        $this->assertTrue($item->isActive());
        $this->assertFalse($item->isActive());
    }

    /** @test */
    public function test_isActive_method_for_base_route()
    {
        $request = m::mock(Request::class);
        app()->bind('request', fn () => $request);

        $request->shouldReceive('is')->withArgs(['', '/'])->once()->andReturn(true);
        $item = new RouteItem('', '', fn () => '/');
        $this->assertTrue($item->isActive());
    }

    /** @test */
    public function test_isActive_method_for_base_locale_route()
    {
        $request = m::mock(Request::class);
        app()->bind('request', fn () => $request);
        app('config')->set('translatable.locales', ['de']);

        $request->shouldReceive('is')->withArgs(['de'])->once()->andReturn(true);
        $item = new RouteItem('', '', fn () => '/de');
        $this->assertTrue($item->isActive());
    }

    /** @test */
    public function test_isActive_method_returns_value()
    {
        $request = m::mock(Request::class);
        app()->bind('request', fn () => $request);

        $request->shouldReceive('is')->withArgs(['dummy/route*'])->once()->andReturn(true);
        $item = new RouteItem('', '', fn () => '/dummy/route');
        $this->assertEquals('dummy-class', $item->isActive('dummy-class'));
    }
}
