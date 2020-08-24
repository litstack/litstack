<?php

namespace Tests\Fields;

use Lit\Crud\BaseField;
use Lit\Crud\Fields\Checkboxes;
use Tests\Traits\InteractsWithFields;
use PHPUnit\Framework\TestCase;

class FieldCheckboxesTest extends TestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        $this->field = $this->getField(Checkboxes::class);
    }

    /** @test */
    public function it_is_base_field()
    {
        $this->assertInstanceOf(BaseField::class, $this->field);
    }

    /** @test */
    public function test_value_cast()
    {
        $this->assertIsArray($this->field->castValue('["a"]'));
        $this->assertIsArray($this->field->castValue(['a']));
    }

    /** @test */
    public function test_options_method()
    {
        $this->field->options([]);
        $this->assertArrayHasKey('options', $this->field->getAttributes());

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->options([]));
    }
}
