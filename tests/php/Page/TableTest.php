<?php

namespace Tests\Page;

use Lit\Exceptions\Traceable\MissingAttributeException;
use Lit\Page\Table\ColumnBuilder;
use Lit\Page\Table\Table;
use Lit\Vue\Component;
use Tests\BackendTestCase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Mockery as m;

class TableTest extends BackendTestCase
{
    /** @test */
    public function test_getComponent_method_returns_component()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('', $builder);

        $this->assertInstanceOf(Component::class, $table->getComponent());
    }

    /** @test */
    public function test_route_prefix_attribute_isset_on_initializing()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('route/prefix', $builder);

        $this->assertEquals('route/prefix', $table->getAttribute('route_prefix'));
    }

    /** @test */
    public function test_default_attributes()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('route/prefix', $builder);

        $this->assertTrue($table->hasAttribute('controls'));
        $this->assertTrue($table->hasAttribute('sortByDefault'));
        $this->assertTrue($table->hasAttribute('search'));
        $this->assertTrue($table->hasAttribute('sortBy'));
        $this->assertTrue($table->hasAttribute('perPage'));
    }

    /** @test */
    public function test_routePrefix_method()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('', $builder);

        $this->assertEquals($table, $table->routePrefix('dumm-route/prefix'));
        $this->assertEquals('dumm-route/prefix', $table->getAttribute('route_prefix'));
    }

    /** @test */
    public function test_singularName_method()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('', $builder);

        $this->assertEquals($table, $table->singularName('dummy name'));
        $this->assertEquals('dummy name', $table->getAttribute('singularName'));
    }

    /** @test */
    public function test_pluralName_method()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('', $builder);

        $this->assertEquals($table, $table->pluralName('dummy names'));
        $this->assertEquals('dummy names', $table->getAttribute('pluralName'));
    }

    /** @test */
    public function test_width_method()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('', $builder);

        $this->assertEquals($table, $table->width(12));
        $this->assertEquals(12, $table->getComponent()->getProp('width'));

        // Test with type [float]:
        $table->width(1 / 2);
        $this->assertEquals(1 / 2, $table->getComponent()->getProp('width'));
    }

    /** @test */
    public function test_perPage_method()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('', $builder);

        $this->assertEquals($table, $table->perPage(7));
        $this->assertEquals(7, $table->getAttribute('perPage'));
    }

    /** @test */
    public function test_sortByDefault_method()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('', $builder);

        $this->assertEquals($table, $table->sortByDefault('name'));
        $this->assertEquals('name', $table->getAttribute('sortByDefault'));
    }

    /** @test */
    public function test_search_method_returns_itself()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('', $builder);

        $this->assertEquals($table, $table->search([]));
    }

    /** @test */
    public function test_cast_method()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('', $builder);

        $this->assertSame($table, $table->cast('active', 'boolean'));
        $this->assertEquals(['active' => 'boolean'], $table->getCasts());
    }

    /** @test */
    public function test_casts_method()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('', $builder);

        $this->assertSame($table, $table->casts(['active' => 'boolean']));
        $this->assertEquals(['active' => 'boolean'], $table->getCasts());
    }

    /** @test */
    public function test_search_method_with_array()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('', $builder);

        $table->search(['first_name', 'last_name']);
        $this->assertEquals(['first_name', 'last_name'], $table->getAttribute('search'));
    }

    /** @test */
    public function test_search_method_arguments()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('', $builder);

        $table->search('first_name', 'last_name');
        $this->assertEquals(['first_name', 'last_name'], $table->getAttribute('search'));
    }

    /** @test */
    public function test_sortBy_method()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('', $builder);

        $this->assertEquals($table, $table->sortBy(['name' => 'Name']));
        $this->assertEquals(['name' => 'Name'], $table->getAttribute('sortBy'));
    }

    /** @test */
    public function test_filter_method()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('', $builder);

        $this->assertEquals($table, $table->filter(['Group' => ['scope' => 'Name']]));
        $this->assertEquals(['Group' => ['scope' => 'Name']], $table->getAttribute('filter'));
    }

    /** @test */
    public function test_model_method()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('', $builder);

        $this->assertEquals($table, $table->model('DummyModel'));
        $this->assertEquals('DummyModel', $this->getUnaccessibleProperty($table, 'model'));
    }

    /** @test */
    public function test_model_method_sets_names()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('', $builder);

        $this->assertEquals($table, $table->model('DummyModel'));
        $this->assertEquals('DummyModel', $table->getAttribute('singularName'));
        $this->assertEquals(Str::plural('DummyModel'), $table->getAttribute('pluralName'));
    }

    /** @test */
    public function test_model_method_does_not_override_names()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('', $builder);

        $table->singularName('singular');
        $table->pluralName('plural');

        $this->assertEquals($table, $table->model('DummyModel'));
        $this->assertEquals('singular', $table->getAttribute('singularName'));
        $this->assertEquals('plural', $table->getAttribute('pluralName'));
    }

    /** @test */
    public function test_query_modifier_receives_query()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();

        $table = new Table('', $builder);

        $query = m::mock(Builder::class);
        $query->shouldReceive('withCasts');
        $table->query(function (...$parameters) use ($query) {
            $this->assertNotEmpty($parameters);
            $this->assertEquals($query, $parameters[0]);
        });

        $table->getQuery($query);
    }

    /** @test */
    public function test_getBuilder_method()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('', $builder);

        $this->assertEquals($builder, $table->getBuilder());
    }

    /** @test */
    public function test_renderering_fails_when_model_is_not_set()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('', $builder);

        $this->expectException(MissingAttributeException::class);
        $table->render();
    }

    /** @test */
    public function it_renders_attributes()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('', $builder);

        $table->model('Model');
        $table->setAttribute('dummyAttribute', 'value');
        $this->assertArrayHasKey('dummyAttribute', $table->render());
    }

    /** @test */
    public function it_renders_columns()
    {
        $builder = m::mock(ColumnBuilder::class)->makePartial();
        $table = new Table('', $builder);

        $table->model('Model');
        $this->assertArrayHasKey('cols', $rendered = $table->render());
        $this->assertEquals($builder, $rendered['cols']);
    }

    /** @test */
    public function test_alphabeticOrder_method()
    {
        $this->assertEquals([
            'title.desc' => 'A -> Z',
            'title.asc'  => 'Z -> A',
        ], Table::alphabeticOrder());

        $this->assertEquals([
            'foo.desc' => 'A -> Z',
            'foo.asc'  => 'Z -> A',
        ], Table::alphabeticOrder('foo'));
    }

    /** @test */
    public function test_numericOrder_method()
    {
        $this->assertEquals([
            'id.desc' => 'New first',
            'id.asc'  => 'Old first',
        ], Table::numericOrder());

        $this->assertEquals([
            'foo.desc' => 'New first',
            'foo.asc'  => 'Old first',
        ], Table::numericOrder('foo'));
    }
}
