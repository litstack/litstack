<?php

namespace Tests;

use Lit\Vue\Component;
use Lit\Vue\Vue;
use PHPUnit\Framework\TestCase;

class VueTest extends TestCase
{
    /** @test */
    public function it_can_be_registered_using_class_string()
    {
        $vue = new Vue;
        $this->assertFalse($vue->has('foo'));
        $this->assertSame($vue, $vue->component('foo', FooComponent::class));
        $this->assertTrue($vue->has('foo'));
        $this->assertInstanceOf(FooComponent::class, $vue->make('foo'));
    }

    /** @test */
    public function it_can_be_registered_using_closure()
    {
        $vue = new Vue;
        $this->assertFalse($vue->has('foo'));
        $this->assertSame($vue, $vue->component('foo', fn ($name) => new FooComponent($name)));
        $this->assertTrue($vue->has('foo'));
        $this->assertInstanceOf(FooComponent::class, $vue->make('foo'));
    }
}

class FooComponent extends Component
{
}
