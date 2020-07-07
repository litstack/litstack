<?php

namespace FjordTest\Fields;

use Fjord\Crud\BaseField;
use Fjord\Crud\Casts\Route as RouteCast;
use Fjord\Crud\Fields\Route;
use Fjord\Crud\Fields\Route\RouteCollectionResolver;
use FjordTest\BackendTestCase;
use FjordTest\Traits\InteractsWithFields;
use Mockery as m;

class FieldRouteTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(Route::class);
    }

    /** @test */
    public function it_is_base_field()
    {
        $this->assertInstanceOf(BaseField::class, $this->field);
    }

    /** @test */
    public function test_cast_returns_integer()
    {
        $cast = m::mock(RouteCast::class);
        app()->bind(RouteCast::class, fn () => $cast);
        $cast->shouldReceive('get')->withArgs([null, '', 'value', []])->andReturn('result');
        $this->assertEquals('result', $this->field->cast('value'));
    }

    /** @test */
    public function test_static_register_method()
    {
        $resolver = m::mock(RouteCollectionResolver::class);
        $closure = fn () => null;
        $resolver->shouldReceive('register')->withArgs(['name', $closure]);
        app()->bind('fjord.crud.route.resolver', fn () => $resolver);
        Route::register('name', $closure);
    }

    public function test_collection_method()
    {
        $this->assertEquals($this->field, $this->field->collection('dummy-collection'));
        $this->assertEquals('dummy-collection', $this->field->collection);
    }
}
