<?php

namespace FjordTest\Page;

use Fjord\Page\BasePage;
use Fjord\Vue\Component;
use Fjord\Vue\Components\BladeComponent;
use FjordTest\BackendTestCase;
use Illuminate\View\View;
use Mockery as m;

class BasePageTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->page = new DummyBasePage;
    }

    /** @test */
    public function test_wrapper()
    {
        $this->assertFalse($this->page->inWrapper());
        $this->page->wrapper('dummy-wrapper', function ($page) {
            $this->assertTrue($page->inWrapper());
        });
        $this->assertFalse($this->page->inWrapper());
    }

    /** @test */
    public function wrapper_returns_wrapping_component()
    {
        $component = $this->page->wrapper('dummy-wrapper', function ($page) {
            //
        });

        $this->assertInstanceOf(Component::class, $component);
        $this->assertEquals('dummy-wrapper', $component->getName());
    }

    /** @test */
    public function test_wrapper_in_wrapper()
    {
        $this->assertFalse($this->page->inWrapper());
        $this->page->wrapper('dummy-wrapper', function ($page) {
            $this->page->wrapper('nested-dummy-wrapper', function ($page) {
                $this->assertTrue($page->inWrapper());
            });

            $this->assertTrue($page->inWrapper());
            $children = $page->getWrapper()->children;
            $this->assertEquals(1, $children->count());
        });
        $this->assertFalse($this->page->inWrapper());
    }

    // /** @test */
    // public function test_view_method_returns_view()
    // {
    //     $this->assertInstanceOf(View::class, $this->page->view('fjord::app'));
    // }

    /** @test */
    public function test_view_method_adds_blade_component()
    {
        $this->page->view('fjord::app');

        $this->assertCount(1, $this->page->getComponents());
        $this->assertInstanceOf(BladeComponent::class, $this->page->getComponents()[0]);
    }

    /** @test */
    public function test_render_method_binds_view_data()
    {
        $view = m::mock(View::class);
        $view->shouldReceive('with')->withArgs([['data' => 'dummy']]);
        $this->page->view($view);
        $this->page->bindToView([
            'data' => 'dummy',
        ]);
        $this->page->render();
    }

    /** @test */
    public function test_render_method()
    {
        $result = $this->page->render();

        $this->assertArrayHasKey('components', $result);
        $this->assertArrayHasKey('props', $result);
    }
}

class DummyBasePage extends BasePage
{
}
