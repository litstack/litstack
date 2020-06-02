<?php

namespace FjordTest\Fields;

use Fjord\Crud\BaseField;
use Fjord\Crud\Fields\Boolean;
use FjordTest\BackendTestCase;
use FjordTest\Traits\InteractsWithFields;

class FieldBooleanTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(Boolean::class);
    }

    /** @test */
    public function it_is_base_field()
    {
        $this->assertInstanceOf(BaseField::class, $this->field);
    }

    /** @test */
    public function test_cast()
    {
        $this->assertIsBool($this->field->cast(1));
        $this->assertIsBool($this->field->cast(0));
    }
}
