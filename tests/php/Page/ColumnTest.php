<?php

namespace Tests\Page;

use Ignite\Exceptions\MissingAttributeException;
use Ignite\Page\Table\Column;
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
    public function test_value_method_with_options()
    {
        $column = new Column();
        $column->value('col', ['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $column->getAttribute('value_options'));
    }

    /** @test */
    public function test_value_method_with_default_value()
    {
        $column = new Column();
        $column->value('foor', [], 'bar');
        $this->assertEquals('bar', $column->getAttribute('default_value'));
    }

    /** @test */
    public function test_right_method()
    {
        $column = new Column();
        $this->assertEquals($column, $column->right());
        $this->assertTrue($column->getAttribute('text_right'));

        $column->right(false);
        $this->assertFalse($column->getAttribute('text_right'));
    }

    /** @test */
    public function test_center_method()
    {
        $column = new Column();
        $this->assertEquals($column, $column->center());
        $this->assertTrue($column->getAttribute('text_center'));

        $column->center(false);
        $this->assertFalse($column->getAttribute('text_center'));
    }

    /** @test */
    public function test_center_and_right_method_cancel_each_other()
    {
        $column = new Column();
        $column->right();
        $column->center();
        $this->assertTrue($column->getAttribute('text_center'));
        $this->assertFalse($column->getAttribute('text_right'));
        $column->right();
        $this->assertFalse($column->getAttribute('text_center'));
        $this->assertTrue($column->getAttribute('text_right'));
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
    public function test_trans_method()
    {
        $column = new Column();
        $this->assertEquals($column, $column->trans('foo'));
        $this->assertTrue($column->getAttribute('trans'));
        $this->assertEquals('foo', $column->getAttribute('value'));
    }

    /** @test */
    public function test_transChoice_method()
    {
        $column = new Column();
        $this->assertEquals($column, $column->transChoice('foo', 'bar'));
        $this->assertTrue($column->getAttribute('trans'));
        $this->assertEquals('foo', $column->getAttribute('value'));
        $this->assertEquals('bar', $column->getAttribute('trans_choice_attribute'));
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
