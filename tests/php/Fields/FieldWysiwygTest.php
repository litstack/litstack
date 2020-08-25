<?php

namespace Tests\Fields;

use Ignite\Crud\BaseField;
use Ignite\Crud\Fields\Traits\FieldHasRules;
use Ignite\Crud\Fields\Traits\TranslatableField;
use Ignite\Crud\Fields\Wysiwyg;
use Tests\Traits\InteractsWithFields;
use Tests\Traits\TestHelpers;
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
