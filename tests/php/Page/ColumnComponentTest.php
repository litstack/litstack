<?php

namespace FjordTest\Page;

use Fjord\Page\Table\Components\ColumnComponent;
use FjordTest\BackendTestCase;
use InvalidArgumentException;

class ColumnComponentTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->component = new ColumnComponent('dummy-component');
    }

    /** @test */
    public function test_label_method()
    {
        $this->assertEquals($this->component, $this->component->label('Name'));
        $this->assertEquals('Name', $this->component->getProp('label'));
    }

    /** @test */
    public function test_value_method()
    {
        $this->assertEquals($this->component, $this->component->value('hello'));
        $this->assertEquals('hello', $this->component->getProp('value'));
    }

    /** @test */
    public function test_small_method()
    {
        $this->assertEquals($this->component, $this->component->small());
        $this->assertTrue($this->component->getProp('small'));

        $this->component->small(false);
        $this->assertFalse($this->component->getProp('small'));
    }

    /** @test */
    public function test_link_method()
    {
        $this->assertEquals($this->component, $this->component->link('/dummy/route'));
        $this->assertEquals('/dummy/route', $this->component->getProp('link'));
    }

    /** @test */
    public function test_sortBy_method()
    {
        $this->assertEquals($this->component, $this->component->sortBy('name'));
        $this->assertEquals('name', $this->component->getProp('sort_by'));
    }

    /** @test */
    public function test_regex_method()
    {
        $this->assertEquals($this->component, $this->component->regex('/([A-Z])\w+/', 'replace'));
        $this->assertEquals('/([A-Z])\w+/', $this->component->getProp('regex'));
        $this->assertEquals('replace', $this->component->getProp('regex_replace'));
    }

    /** @test */
    public function test_regex_method_fails_for_incorrect_expression()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->component->regex('incorrect', 'replace');
    }

    /** @test */
    public function test_stripHtml_method()
    {
        $this->assertEquals($this->component, $this->component->stripHtml());
        $this->assertTrue($this->component->getProp('strip_html'));

        $this->component->stripHtml(false);
        $this->assertFalse($this->component->getProp('strip_html'));
    }

    /** @test */
    public function test_maxChars_method()
    {
        $this->assertEquals($this->component, $this->component->maxChars(7));
        $this->assertEquals(7, $this->component->getProp('max_chars'));
    }
}
