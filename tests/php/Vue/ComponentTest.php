<?php

namespace Tests;

use Ignite\Crud\FieldDependency;
use Ignite\Vue\Component;
use Ignite\Vue\Event;
use PHPUnit\Framework\TestCase;

class ComponentTest extends TestCase
{
    /** @test */
    public function test_constructor_sets_name()
    {
        $component = new Component('foo');
        $this->assertSame('foo', $component->getName());
    }

    /** @test */
    public function test_constructor_calls_beforeMount_method()
    {
        $component = new BeforeMountComponentTest('foo');
        $this->assertTrue($component->success);
    }

    /** @test */
    public function test_slot_method()
    {
        $component = new Component('foo');
        $this->assertSame($component, $component->slot('default'));
        $this->assertSame('default', $component->getSlot());
    }

    /** @test */
    public function test_child_method()
    {
        $component = new Component('foo');
        $this->assertSame($component, $component->child('child'));
        $this->assertCount(1, $component->getChildren());
        $this->assertSame('child', $component->getChildren()[0]);
    }

    /** @test */
    public function test_content_method_sets_child()
    {
        $component = new Component('foo');
        $this->assertSame($component, $component->content('child'));
        $this->assertCount(1, $component->getChildren());
        $this->assertSame('child', $component->getChildren()[0]);
    }

    /** @test */
    public function test_prop_method()
    {
        $component = new Component('foo');
        $this->assertSame($component, $component->prop('foo', 'bar'));
        $this->assertTrue($component->hasProp('foo'));
        $this->assertSame('bar', $component->getProp('foo'));
    }

    /** @test */
    public function test_bind_method()
    {
        $component = new Component('foo');
        $this->assertSame($component, $component->bind(['foo' => 'bar', 'hello' => 'world']));
        $this->assertTrue($component->hasProp('foo'));
        $this->assertTrue($component->hasProp('hello'));
        $this->assertSame('bar', $component->getProp('foo'));
        $this->assertSame('world', $component->getProp('hello'));

        // Testing getProps method.
        $this->assertSame(['foo' => 'bar', 'hello' => 'world'], $component->getProps());
    }

    /** @test */
    public function test_on_method()
    {
        $component = new Component('foo');
        $this->assertInstanceOf(Event::class, $component->on('click', 'handler'));
        $this->assertSame('handler', $component->getEventHandler('click')->getHandler());
    }

    /** @test */
    public function test_class_method()
    {
        $component = new Component('foo');
        $this->assertSame($component, $component->class('foo'));
        $this->assertCount(1, $component->getClasses());
        $this->assertSame('foo', $component->getClasses()[0]);
    }

    /** @test */
    public function test_class_method_doesnt_duplicate_classes()
    {
        $component = new Component('foo');
        $this->assertSame($component, $component->class('foo'));
        $this->assertSame($component, $component->class('foo'));
        $this->assertSame($component, $component->class('bar'));
        $this->assertSame($component, $component->class('bar'));
        $this->assertCount(2, $component->getClasses());
    }

    /** @test */
    public function test_prop_getter()
    {
        $component = new Component('foo');
        $this->assertSame($component, $component->prop('foo', 'bar'));
        $this->assertSame('bar', $component->foo);
    }

    /** @test */
    public function test_render_method()
    {
        $component = new Component('foo');
        $rendered = $component->render();
        $this->assertArrayHasKey('name', $rendered);
        $this->assertArrayHasKey('props', $rendered);
        $this->assertArrayHasKey('events', $rendered);
        $this->assertArrayHasKey('slot', $rendered);
        $this->assertArrayHasKey('children', $rendered);
        $this->assertArrayHasKey('classes', $rendered);
    }

    /** @test */
    public function test_render_method_calls_lifecycle_hooks()
    {
        $component = new RenderComponentTest('foo');
        $rendered = $component->render();
        $this->assertTrue($component->mountedCalled);
        $this->assertTrue($component->renderedCalled);
    }

    /** @test */
    public function it_adds_dependency()
    {
        $component = new Component('foo');
        $component->when('bar', 'baz');

        $this->assertCount(1, $component->getDependencies());
        $this->assertInstanceOf(FieldDependency::class, $component->getDependencies()[0]);
        $dependency = $component->getDependencies()[0];
        $this->assertSame('when', $dependency->getCondition());
        $this->assertSame('bar', $dependency->getAttributeName());
        $this->assertSame('baz', $dependency->getValue());
    }

    /** @test */
    public function it_sets_dependency_dependor()
    {
        $component = new Component('foo');
        $component->dependsOn('hello');
        $component->when('bar', 'baz');

        $this->assertCount(1, $component->getDependencies());
        $this->assertInstanceOf(FieldDependency::class, $component->getDependencies()[0]);
        $dependency = $component->getDependencies()[0];
        $this->assertSame('hello', $dependency->getDependor());
    }
}

class BeforeMountComponentTest extends Component
{
    public $success = false;

    public function beforeMount()
    {
        $this->success = true;
    }
}

class RenderComponentTest extends Component
{
    public $mountedCalled = false;
    public $renderedCalled = false;

    public function mounted()
    {
        $this->mountedCalled = true;
    }

    public function rendered($rendered)
    {
        $this->renderedCalled = true;
    }
}
