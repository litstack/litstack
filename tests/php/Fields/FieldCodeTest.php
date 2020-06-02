<?php

namespace FjordTest\Fields;

use Fjord\Crud\BaseField;
use Fjord\Crud\Fields\Code;
use FjordTest\BackendTestCase;
use FjordTest\Traits\InteractsWithFields;
use Fjord\Crud\Fields\Traits\FieldHasRules;

class FieldCodeTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(Code::class);
    }

    /** @test */
    public function it_can_have_rules()
    {
        $this->assertHasTrait(FieldHasRules::class, $this->field);
    }

    /** @test */
    public function it_is_base_field()
    {
        $this->assertInstanceOf(BaseField::class, $this->field);
    }

    /** @test */
    public function test_options_method()
    {
        $this->field->options(['a' => 'b']);
        $this->assertArrayHasKey('options', $this->field->getAttributes());
        $this->assertEquals(['a' => 'b'], $this->field->getAttribute('options'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->options([]));
    }

    /** @test */
    public function test_language_method()
    {
        $this->field->language('dummy_language');
        $this->assertArrayHasKey('language', $this->field->getAttributes());
        $this->assertEquals('dummy_language', $this->field->getAttribute('language'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->options([]));
    }

    /** @test */
    public function test_line_method()
    {
        $this->field->line();
        $this->assertTrue($this->field->getAttribute('line'));

        $this->field->line(false);
        $this->assertFalse($this->field->getAttribute('line'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->line());
    }

    /** @test */
    public function test_lineNumbers_method()
    {
        $this->field->lineNumbers(25);
        $this->assertArrayHasKey('lineNumbers', $this->field->getAttributes());
        $this->assertEquals(25, $this->field->getAttribute('lineNumbers'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->lineNumbers(25));
    }

    /** @test */
    public function test_theme_method()
    {
        $this->field->theme('dummy_theme');
        $this->assertArrayHasKey('theme', $this->field->getAttributes());
        $this->assertEquals('dummy_theme', $this->field->getAttribute('theme'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->theme(''));
    }

    /** @test */
    public function test_tabSize_method()
    {
        $this->field->tabSize(25);
        $this->assertArrayHasKey('tabSize', $this->field->getAttributes());
        $this->assertEquals(25, $this->field->getAttribute('tabSize'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->tabSize(25));
    }
}
