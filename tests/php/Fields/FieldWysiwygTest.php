<?php

namespace FjordTest\Fields;

use Fjord\Crud\BaseField;
use Fjord\Crud\Fields\Traits\FieldHasRules;
use Fjord\Crud\Fields\Traits\TranslatableField;
use Fjord\Crud\Fields\Wysiwyg;
use FjordTest\Traits\InteractsWithFields;
use FjordTest\Traits\TestHelpers;
use PHPUnit\Framework\TestCase;

class FieldWysiwygTest extends TestCase
{
    use InteractsWithFields, TestHelpers;

    public function setUp(): void
    {
        $this->field = $this->getField(Wysiwyg::class);
    }

    /** @test */
    public function it_can_have_rules()
    {
        $this->assertHasTrait(FieldHasRules::class, $this->field);
    }

    /** @test */
    public function it_can_be_translatable()
    {
        $this->assertHasTrait(TranslatableField::class, $this->field);
    }

    /** @test */
    public function it_is_base_field()
    {
        $this->assertInstanceOf(BaseField::class, $this->field);
    }

    /** @test */
    public function test_colors_method()
    {
        $this->field->colors('red', 'green');
        $this->assertEquals(['red', 'green'], $this->field->getAttribute('colors'));
        $this->field->colors(['red', 'green']);
        $this->assertEquals(['red', 'green'], $this->field->getAttribute('colors'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->colors('red'));
    }

    /** @test */
    public function test_css_method()
    {
        $this->field->css(__DIR__.'/wysiwyg.css');
        $this->assertStringContainsString('dummycss', $this->field->getAttribute('css'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->css(__DIR__.'/wysiwyg.css'));
    }

    /** @test */
    public function test_css_method_throws_exception_for_not_existing_path()
    {
        $this->expectException(\Illuminate\Contracts\Filesystem\FileNotFoundException::class);
        $this->field->css('other_path');
    }
}
