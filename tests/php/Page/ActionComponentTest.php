<?php

namespace Lit\Test\Page;

use Ignite\Page\Actions\ActionComponent;
use Ignite\Page\Actions\ActionModal;
use Ignite\Page\RunActionEvent;
use Ignite\Vue\Component;
use InvalidArgumentException;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class ActionComponentTest extends TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_has_run_event()
    {
        $action = new ActionComponent(DummyAction::class, 'foo');
        $events = $action->getEvents();
        $this->assertArrayHasKey('run', $events);
        $this->assertSame(RunActionEvent::class, $events['run']);
    }

    /** @test */
    public function it_binds_action_class_to_event_data()
    {
        $action = new ActionComponent(DummyAction::class, 'foo');
        $this->assertEquals(['action' => DummyAction::class], $action->getProp('eventData'));
    }

    /** @test */
    public function test_wrapper_method()
    {
        $wrapper = m::mock(Component::class);
        $action = new ActionComponent(DummyAction::class, 'foo');
        $action->wrapper($wrapper);
        $this->assertSame($wrapper, $action->getProp('wrapper'));
    }

    /** @test */
    public function it_sets_initial_wrapper()
    {
        $wrapper = m::mock(Component::class);
        $action = new ActionComponent(DummyAction::class, 'foo', $wrapper);
        $this->assertSame($wrapper, $action->getProp('wrapper'));
    }

    /** @test */
    public function test_setEventHandler_method()
    {
        $action = new ActionComponent(DummyAction::class, 'foo');
        $action->setEventHandler(DummyRunActionEvent::class);
        $events = $action->getEvents();
        $this->assertArrayHasKey('run', $events);
        $this->assertSame(DummyRunActionEvent::class, $events['run']->getHandler());
    }

    /** @test */
    public function test_test_setEventHandler_method_fails_when_handler_is_invalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $action = new ActionComponent(DummyAction::class, 'foo');
        $action->setEventHandler(InvalidDummyRunActionEvent::class);
    }

    /** @test */
    public function test_addEventData_method()
    {
        $action = new ActionComponent(DummyAction::class, 'foo');
        $action->addEventData(['foo' => 'bar']);
        $data = $action->getProp('eventData');
        $this->assertArrayHasKey('foo', $data);
        $this->assertSame('bar', $data['foo']);
    }

    /** @test */
    public function test_it_sets_modal_when_action_has_modal_method()
    {
        $action = new ActionComponent(DummyAction::class, 'foo');
        $this->assertNull($action->getProp('modal'));
        $action = new ActionComponent(DummyActionWithModal::class, 'foo');
        $this->assertInstanceOf(ActionModal::class, $action->getProp('modal'));
    }

    /** @test */
    public function it_authorizes_wrapper()
    {
        $wrapper = m::mock(Component::class);
        $wrapper->shouldReceive('authorize')->withArgs([false])->once();
        $action = new ActionComponent(DummyAction::class, 'foo', $wrapper);
        $action->authorize(false);
    }
}

class DummyRunActionEvent extends RunActionEvent
{
}

class DummyAction
{
}

class DummyActionWithModal
{
    public function modal($modal)
    {
        //
    }
}
