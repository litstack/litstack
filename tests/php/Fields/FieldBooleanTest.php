<?php

namespace FjordTest\Fields;

use Fjord\Crud\Fields\Boolean;
use FjordTest\BackendTestCase;
use FjordTest\Traits\InteractsWithFields;
use Fjord\Crud\Fields\Concerns\FormItemWrapper;

class FieldBooleanTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(Boolean::class);
    }

    /** @test */
    public function it_has_form_item_wrapper()
    {
        $this->assertHasTrait(FormItemWrapper::class, $this->field);
    }

    /** @test */
    public function test_cast()
    {
        $this->assertIsBool($this->field->cast(1));
        $this->assertIsBool($this->field->cast(0));
    }
}
