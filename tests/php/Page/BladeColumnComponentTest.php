<?php

namespace FjordTest\Page;

use Fjord\Page\Table\Components\BladeColumnComponent;
use Fjord\Page\Table\Components\ColumnComponent;
use Illuminate\Contracts\View\View;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class BladeColumnComponentTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->component = new BladeColumnComponent();
    }

    /** @test */
    public function it_is_a_column_component()
    {
        $this->assertInstanceOf(ColumnComponent::class, $this->component);
    }

    /** @test */
    public function it_has_correct_name()
    {
        $this->assertEquals('fj-blade', $this->component->getName());
    }

    /** @test */
    public function test_view_method()
    {
        $view = m::mock(View::class);
        $this->assertEquals($this->component, $this->component->view($view));
    }

    /** @test */
    public function it_renders_view()
    {
        $view = m::mock(View::class);
        $view->shouldReceive('render')->andReturn('rendered');
        $this->component->view($view);
        $rendered = $this->component->render();
        $this->assertArrayHasKey('view', $rendered['props']);
        $this->assertEquals('rendered', $rendered['props']['view']);
    }
}
