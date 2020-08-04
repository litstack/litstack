<?php

namespace FjordTest\Page;

use Fjord\Page\Table\Column;
use Fjord\Page\Table\ColumnBuilder;
use Fjord\Page\Table\Components\BladeColumnComponent;
use Fjord\Page\Table\Components\ColumnComponent;
use FjordTest\Traits\InteractsWithComponents;
use FjordTest\Traits\TestHelpers;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\View;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class ColumnBuilderTest extends TestCase
{
    use TestHelpers, InteractsWithComponents;

    public function setUp(): void
    {
        $this->setupApplication();
    }

    /** @test */
    public function test_col_method_returns_column()
    {
        $builder = new ColumnBuilder;
        $this->assertInstanceOf(Column::class, $builder->col());
    }

    /** @test */
    public function test_col_method_registers_column()
    {
        $builder = new ColumnBuilder;
        $builder->col();
        $columns = $this->getUnaccessibleProperty($builder, 'columns');
        $this->assertCount(1, $columns);
        $this->assertInstanceOf(Column::class, $columns[0]);
    }

    /** @test */
    public function test_component_method_returns_table_component()
    {
        $builder = new ColumnBuilder;
        $this->assertInstanceOf(ColumnComponent::class, $builder->component('dummy-component'));
    }

    /** @test */
    public function test_component_method_registers_component()
    {
        $builder = new ColumnBuilder;
        $builder->component('dummy-component');
        $columns = $this->getUnaccessibleProperty($builder, 'columns');
        $this->assertCount(1, $columns);
        $this->assertInstanceOf(ColumnComponent::class, $columns[0]);
    }

    /** @test */
    public function test_view_method_returns_view()
    {
        $builder = new ColumnBuilder;
        $view = m::mock(ViewContract::class);
        View::partialMock()->shouldReceive('make')->withArgs(['dummy_view'])->andReturn($view);
        $this->assertEquals($view, $builder->view('dummy_view'));
    }

    /** @test */
    public function test_view_method_registers_blade_table_component()
    {
        $builder = new ColumnBuilder;
        $view = m::mock(ViewContract::class);
        View::partialMock()->shouldReceive('make')->withArgs(['dummy_view'])->andReturn($view);
        $builder->view('dummy_view');
        $columns = $this->getUnaccessibleProperty($builder, 'columns');
        $this->assertCount(1, $columns);
        $this->assertInstanceOf(BladeColumnComponent::class, $columns[0]);
    }

    /** @test */
    public function test_toggle_method_returns_table_component()
    {
        $builder = new ColumnBuilder;
        $result = $builder->toggle('active');
        $this->assertInstanceOf(ColumnComponent::class, $result);
        $this->assertEquals('fj-col-toggle', $result->getName());
    }

    /** @test */
    public function test_toggle_method_sets_link_to_false()
    {
        $builder = new ColumnBuilder;
        $result = $builder->toggle('active');
        $this->assertFalse($result->getProp('link'));
    }

    /** @test */
    public function test_toggle_method_sets_local_key()
    {
        $builder = new ColumnBuilder;
        $result = $builder->toggle('active');
        $this->assertEquals('active', $result->getProp('local_key'));
    }

    /** @test */
    public function test_image_method_returns_table_component()
    {
        $builder = new ColumnBuilder;
        $result = $builder->image('Image');
        $this->assertInstanceOf(ColumnComponent::class, $result);
        $this->assertEquals('fj-col-image', $result->getName());
    }

    /** @test */
    public function test_image_method_sets_label()
    {
        $builder = new ColumnBuilder;
        $result = $builder->image('Image');
        $this->assertEquals('Image', $result->getProp('label'));
    }

    /** @test */
    public function test_relation_method_returns_table_component()
    {
        $builder = new ColumnBuilder;
        $result = $builder->relation('User');
        $this->assertInstanceOf(ColumnComponent::class, $result);
        $this->assertEquals('fj-col-crud-relation', $result->getName());
    }

    /** @test */
    public function test_relation_method_sets_label()
    {
        $builder = new ColumnBuilder;
        $result = $builder->relation('User');
        $this->assertEquals('User', $result->getProp('label'));
    }

    /** @test */
    public function it_renders_columns()
    {
        $builder = new ColumnBuilder;
        $this->setUnaccessibleProperty($builder, 'columns', ['column']);
        $this->assertEquals(['column'], $builder->render());
    }
}
