<?php

namespace Tests\Page;

use Lit\Page\Table\Components\ToggleComponent;
use PHPUnit\Framework\TestCase;

class ToggleComponentTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->component = new ToggleComponent('foo');
    }

    /** @test */
    public function test_routePrefix_method()
    {
        $this->assertEquals($this->component, $this->component->routePrefix('foo'));
        $this->assertEquals('foo', $this->component->getProp('routePrefix'));
    }
}
