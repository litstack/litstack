<?php

namespace FjordTest\Fields;

use Fjord\Crud\Fields\Checkboxes;
use FjordTest\BackendTestCase;
use FjordTest\Traits\InteractsWithFields;
use Fjord\Crud\Fields\Concerns\FormItemWrapper;

class FieldCheckboxesTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(Checkboxes::class);
    }

    /** @test */
    public function it_has_form_item_wrapper()
    {
        $this->assertHasTrait(FormItemWrapper::class, $this->field);
    }

    /** @test */
    public function test_cast()
    {
        $this->assertIsArray($this->field->cast('["a"]'));
        $this->assertIsArray($this->field->cast(["a"]));
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
