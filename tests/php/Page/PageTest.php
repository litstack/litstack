<?php

namespace Tests\Page;

use Ignite\Exceptions\NotLoggedInException;
use Ignite\Page\Header;
use Ignite\Page\Navigation;
use Ignite\Page\Page;
use Tests\BackendTestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Mockery as m;

class PageTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->navigation = m::mock(Navigation::class);
        $this->header = m::mock(Header::class);
        $this->page = new Page;
        $this->setUnaccessibleProperty($this->page, 'navigation', $this->navigation);
        $this->setUnaccessibleProperty($this->page, 'header', $this->header);
    }

    /** @test */
    public function test_title_method()
    {
        $this->header->shouldReceive('setTitle')->withArgs(['dummy title']);
        $this->assertEquals($this->page, $this->page->title('dummy title'));
    }

    /** @test */
    public function test_getTitle_method()
    {
        $this->header->shouldReceive('getTitle')->andReturn('dummy title');
        $this->assertEquals('dummy title', $this->page->getTitle());
    }

    /** @test */
    public function test_navigationLeft_method()
    {
        $this->navigation->shouldReceive('getLeftSlot')->andReturn('slot');
        $this->assertEquals('slot', $this->page->navigationLeft());
    }

    /** @test */
    public function test_navigationRight_method()
    {
        $this->navigation->shouldReceive('getRightSlot')->andReturn('slot');
        $this->assertEquals('slot', $this->page->navigationRight());
    }

    /** @test */
    public function test_navigationControls_method()
    {
        $this->navigation->shouldReceive('getControlsSlot')->andReturn('slot');
        $this->assertEquals('slot', $this->page->navigationControls());
    }

    /** @test */
    public function test_headerLeft_method()
    {
        $this->header->shouldReceive('getLeftSlot')->andReturn('slot');
        $this->assertEquals('slot', $this->page->headerLeft());
    }

    /** @test */
    public function test_headerRight_method()
    {
        $this->header->shouldReceive('getRightSlot')->andReturn('slot');
        $this->assertEquals('slot', $this->page->headerRight());
    }

    /** @test */
    public function test_render_method_needs_logged_in_lit_user()
    {
        $slot = m::mock('slot');
        $slot->shouldReceive('getViews')->andReturn([]);
        $this->navigation->shouldReceive('getLeftSlot')->andReturn($slot);
        $this->navigation->shouldReceive('getRightSlot')->andReturn($slot);
        $this->navigation->shouldReceive('getControlsSlot')->andReturn($slot);
        $this->header->shouldReceive('getLeftSlot')->andReturn($slot);
        $this->header->shouldReceive('getRightSlot')->andReturn($slot);

        $this->expectException(NotLoggedInException::class);
        $this->page->render();
    }

    /** @test */
    public function test_render_method_binds_view_data_to_slots()
    {
        $view = m::mock(View::class);
        $view->shouldReceive('with')->withArgs([['data' => 'dummy']]);
        $slot = m::mock('slot');
        $slot->shouldReceive('getViews')->once()->andReturn([$view]);
        $slot->shouldReceive('getViews')->andReturn([]);
        $this->navigation->shouldReceive('getLeftSlot')->andReturn($slot);
        $this->navigation->shouldReceive('getRightSlot')->andReturn($slot);
        $this->navigation->shouldReceive('getControlsSlot')->andReturn($slot);
        $this->header->shouldReceive('getLeftSlot')->andReturn($slot);
        $this->header->shouldReceive('getRightSlot')->andReturn($slot);

        $guard = m::mock('guard');
        $guard->shouldReceive('user')->andReturn(true);
        Auth::partialMock()->shouldReceive('guard')->andReturn($guard);
        $this->page->bindToView(['data' => 'dummy']);
        $this->page->render();
    }

    /** @test */
    public function test_render_method_returns_required_attributes()
    {
        $slot = m::mock('slot');
        $slot->shouldReceive('getViews')->andReturn([]);
        $this->navigation->shouldReceive('getLeftSlot')->andReturn($slot);
        $this->navigation->shouldReceive('getRightSlot')->andReturn($slot);
        $this->navigation->shouldReceive('getControlsSlot')->andReturn($slot);
        $this->header->shouldReceive('getLeftSlot')->andReturn($slot);
        $this->header->shouldReceive('getRightSlot')->andReturn($slot);

        $guard = m::mock('guard');
        $guard->shouldReceive('user')->andReturn(true);
        Auth::partialMock()->shouldReceive('guard')->andReturn($guard);

        $result = $this->page->render();

        $this->assertArrayHasKey('navigation', $result);
        $this->assertEquals($this->navigation, $result['navigation']);
        $this->assertArrayHasKey('header', $result);
        $this->assertEquals($this->header, $result['header']);
        $this->assertArrayHasKey('back', $result);
    }
}
