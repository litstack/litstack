<?php

namespace FjordTest\Vue;

use Fjord\Vue\Component;
use Fjord\Vue\Page\Page;
use FjordTest\BackendTestCase;

class PageTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->container = new TestContainer;
    }

    /** @test */
    public function test_wrapper()
    {
        $this->assertFalse($this->container->inWrapper());
        $this->container->wrapper('dummy-wrapper', function ($container) {
            $this->assertTrue($container->inWrapper());
        });
        $this->assertFalse($this->container->inWrapper());
    }

    /** @test */
    public function wrapper_returns_wrapping_component()
    {
        $component = $this->container->wrapper('dummy-wrapper', function ($container) {
            //
        });

        $this->assertInstanceOf(Component::class, $component);
        $this->assertEquals('dummy-wrapper', $component->getName());
    }

    /** @test */
    public function test_wrapper_in_wrapper()
    {
        $this->assertFalse($this->container->inWrapper());
        $this->container->wrapper('dummy-wrapper', function ($container) {
            $this->container->wrapper('nested-dummy-wrapper', function ($container) {
                $this->assertTrue($container->inWrapper());
            });

            $this->assertTrue($container->inWrapper());
            $children = $container->getWrapper()->children;
            $this->assertEquals(1, $children->count());
        });
        $this->assertFalse($this->container->inWrapper());
    }
}

class TestContainer extends Page
{
}
