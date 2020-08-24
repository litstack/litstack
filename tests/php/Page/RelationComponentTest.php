<?php

namespace Tests\Page;

use Lit\Page\Table\Components\RelationComponent;
use PHPUnit\Framework\TestCase;

class RelationComponentTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->component = new RelationComponent('foo');
    }

    /** @test */
    public function test_related_method()
    {
        $this->assertEquals($this->component, $this->component->related('foo'));
        $this->assertEquals('foo', $this->component->getProp('related'));
    }

    /** @test */
    public function test_routePrefix_method()
    {
        $this->assertEquals($this->component, $this->component->routePrefix('foo'));
        $this->assertEquals('foo', $this->component->getProp('routePrefix'));
    }

    /** @test */
    public function test_link_method()
    {
        $this->assertEquals($this->component, $this->component->link('foo'));
        $this->assertEquals(null, $this->component->getProp('link'));
    }
}
