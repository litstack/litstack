<?php

namespace Tests\Fields;

use Ignite\Crud\BaseField;
use Ignite\Crud\Fields\Boolean;
use Tests\Traits\InteractsWithFields;
use PHPUnit\Framework\TestCase;

class FieldBooleanTest extends TestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        $this->field = $this->getField(Boolean::class);
    }

    /** @test */
    public function it_is_base_field()
    {
        $this->assertInstanceOf(BaseField::class, $this->field);
    }

    /** @test */
    public function test_castValue()
    {
        $this->assertIsBool($this->field->castValue(1));
        $this->assertIsBool($this->field->castValue(0));
    }
}
