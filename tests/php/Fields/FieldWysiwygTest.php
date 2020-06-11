<?php

namespace FjordTest\Fields;

use Fjord\Crud\BaseField;
use Fjord\Crud\Fields\Wysiwyg;
use FjordTest\BackendTestCase;
use FjordTest\Traits\InteractsWithFields;
use Fjord\Crud\Fields\Traits\FieldHasRules;
use Fjord\Crud\Fields\Traits\TranslatableField;

class FieldWysiwygTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();

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
        $this->field->css('dummypath');
        $this->assertEquals('dummypath', $this->field->getAttribute('css'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->css('dummypath'));
    }
}
