<?php

namespace FjordTest\Fields;

use Fjord\Crud\BaseField;
use Fjord\Crud\Fields\Boolean;
use FjordTest\Traits\InteractsWithFields;
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
    public function test_cast()
    {
        $this->assertIsBool($this->field->cast(1));
        $this->assertIsBool($this->field->cast(0));
    }
}
