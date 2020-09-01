<?php

namespace Tests\Fields;

use Ignite\Crud\BaseField;
use Ignite\Crud\Casts\Route as RouteCast;
use Ignite\Crud\Fields\Route;
use Ignite\Crud\Fields\Route\RouteCollectionResolver;
use Mockery as m;
use Tests\BackendTestCase;
use Tests\Traits\InteractsWithFields;

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
    public function test_value_cast_returns_integer()
    {
        $cast = m::mock(RouteCast::class);
        app()->bind(RouteCast::class, fn () => $cast);
        $cast->shouldReceive('get')->withArgs([null, '', 'value', []])->andReturn('result');
        $this->assertEquals('result', $this->field->castValue('value'));
    }

    /** @test */
    public function test_static_register_method()
    {
        $resolver = m::mock(RouteCollectionResolver::class);
        $closure = fn () => null;
        $resolver->shouldReceive('register')->withArgs(['name', $closure]);
        app()->bind('lit.crud.route.resolver', fn () => $resolver);
        Route::register('name', $closure);
    }

    /** @test */
    public function test_collection_method()
    {
        $this->assertEquals($this->field, $this->field->collection('dummy-collection'));
        $this->assertEquals('dummy-collection', $this->field->collection);
    }
}
