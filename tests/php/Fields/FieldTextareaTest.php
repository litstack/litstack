<?php

namespace FjordTest\Fields;

use Fjord\Crud\BaseField;
use Fjord\Crud\Fields\Textarea;
use Fjord\Crud\Fields\Traits\FieldHasRules;
use Fjord\Crud\Fields\Traits\TranslatableField;
use FjordTest\BackendTestCase;
use FjordTest\Traits\InteractsWithFields;

class FieldTextareaTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(Textarea::class);
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
    public function test_maxChars_method()
    {
        $this->field->maxChars(5);
        $this->assertArrayHasKey('maxChars', $this->field->getAttributes());
        $this->assertEquals(5, $this->field->getAttribute('maxChars'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->maxChars(5));
    }

    /** @test */
    public function test_rows_method()
    {
        $this->field->rows(10);
        $this->assertArrayHasKey('rows', $this->field->getAttributes());
        $this->assertEquals(10, $this->field->getAttribute('rows'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->rows(5));
    }

    /** @test */
    public function test_maxRows_method()
    {
        $this->field->maxRows(10);
        $this->assertArrayHasKey('maxRows', $this->field->getAttributes());
        $this->assertEquals(10, $this->field->getAttribute('maxRows'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->maxRows(5));
    }
}
