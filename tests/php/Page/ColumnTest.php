<?php

namespace FjordTest\Page;

use Fjord\Exceptions\MissingAttributeException;
use Fjord\Page\Table\Column;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ColumnTest extends TestCase
{
    /** @test */
    public function test_constructor_sets_label_and_value()
    {
        $column = new Column('Dummy');
        $this->assertEquals('Dummy', $column->getAttribute('label'));
        $this->assertEquals('Dummy', $column->getAttribute('value'));
    }

    /** @test */
    public function test_label_method()
    {
        $column = new Column();
        $this->assertEquals($column, $column->label('My Label'));
        $this->assertEquals('My Label', $column->getAttribute('label'));
    }

    /** @test */
    public function test_value_method()
    {
        $column = new Column();
        $this->assertEquals($column, $column->value('hello'));
        $this->assertEquals('hello', $column->getAttribute('value'));
    }

    /** @test */
    public function test_small_method()
    {
        $column = new Column();
        $this->assertEquals($column, $column->small());
        $this->assertTrue($column->getAttribute('small'));

        $column->small(false);
        $this->assertFalse($column->getAttribute('small'));
    }

    /** @test */
    public function test_link_method()
    {
        $column = new Column();
        $this->assertEquals($column, $column->link('/dummy/route'));
        $this->assertEquals('/dummy/route', $column->getAttribute('link'));
    }

    /** @test */
    public function test_sortBy_method()
    {
        $column = new Column();
        $this->assertEquals($column, $column->sortBy('name'));
        $this->assertEquals('name', $column->getAttribute('sort_by'));
    }

    /** @test */
    public function test_regex_method()
    {
        $column = new Column();
        $this->assertEquals($column, $column->regex('/([A-Z])\w+/', 'replace'));
        $this->assertEquals('/([A-Z])\w+/', $column->getAttribute('regex'));
        $this->assertEquals('replace', $column->getAttribute('regex_replace'));
    }

    /** @test */
    public function test_regex_method_fails_for_incorrect_expression()
    {
        $column = new Column();
        $this->expectException(InvalidArgumentException::class);
        $column->regex('incorrect', 'replace');
    }

    /** @test */
    public function test_stripHtml_method()
    {
        $column = new Column();
        $this->assertEquals($column, $column->stripHtml());
        $this->assertTrue($column->getAttribute('strip_html'));

        $column->stripHtml(false);
        $this->assertFalse($column->getAttribute('strip_html'));
    }

    /** @test */
    public function test_maxChars_method()
    {
        $column = new Column();
        $this->assertEquals($column, $column->maxChars(7));
        $this->assertEquals(7, $column->getAttribute('max_chars'));
    }

    /** @test */
    public function it_renders_attributes()
    {
        $column = new Column('');
        $column->setAttribute('dummy', 'value');
        $rendered = $column->render();
        $this->assertArrayHasKey('dummy', $rendered);
    }

    /** @test */
    public function test_render_method_fails_when_label_is_missing()
    {
        $column = new Column();
        $this->expectException(MissingAttributeException::class);
        $rendered = $column->render();
    }
}
