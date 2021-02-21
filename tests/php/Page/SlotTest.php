<?php

namespace Tests\Page;

use Ignite\Contracts\Page\ActionFactory;
use Ignite\Page\BasePage;
use Ignite\Page\Slot;
use Ignite\Vue\Component;
use Illuminate\Contracts\View\View;
use InvalidArgumentException;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Tests\Traits\InteractsWithComponents;

class SlotTest extends TestCase
{
    use InteractsWithComponents;

    public function setUp(): void
    {
        $this->setupApplication();
    }

    public function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function test_view_method()
    {
        $page = m::mock(BasePage::class);
        $slot = new Slot($page);
        $view = m::mock(View::class);
        $slot->view($view);
        $components = $slot->getComponents();
        $this->assertCount(1, $components);
        $this->assertSame('lit-blade', $components[0]->getName());
        $views = $slot->getViews();
        $this->assertCount(1, $views);
        $this->assertSame($view, $views[0]);
    }

    /** @test */
    public function test_action_method_fails_when_slot_doesnt_have_an_action_factory()
    {
        $this->expectException(InvalidArgumentException::class);
        $page = m::mock(BasePage::class);
        $slot = new Slot($page);
        $slot->action('title', 'action');
    }

    /** @test */
    public function test_action_method()
    {
        $component = m::mock(Component::class);
        $component->shouldReceive('getProp')->withArgs(['wrapper'])->once()->andReturn('wrapper');
        $actionFactory = m::mock(ActionFactory::class);
        $actionFactory->shouldReceive('make')->withArgs(['foo', DummySlotAction::class])->once()->andReturn($component);
        $page = m::mock(BasePage::class);
        $page->shouldReceive('bindAction')->withArgs([$component])->once();
        $slot = new Slot($page, $actionFactory);
        $this->assertSame('wrapper', $slot->action('foo', DummySlotAction::class));
    }
}

class DummySlotAction
{
}
