<?php

namespace Tests\Fields;

use Lit\Crud\BaseField;
use Lit\Crud\Fields\Range;
use Tests\Traits\InteractsWithFields;
use PHPUnit\Framework\TestCase;

class FieldRangeTest extends TestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        $this->field = $this->getField(Range::class);
    }

    /** @test */
    public function it_is_base_field()
    {
        $this->assertInstanceOf(BaseField::class, $this->field);
    }

    /** @test */
    public function test_value_cast_returns_integer()
    {
        $this->assertIsInt($this->field->castValue('1'));
        $this->assertIsInt($this->field->castValue('1.5'));
        $this->assertIsInt($this->field->castValue('text'));
    }

    /** @test */
    public function test_it_has_min_and_max_defaults()
    {
        $this->assertArrayHasKey('step', $this->field->getAttributes());
        $this->assertArrayHasKey('min', $this->field->getAttributes());
        $this->assertIsNumeric($this->field->getAttribute('step'));
        $this->assertIsNumeric($this->field->getAttribute('min'));
    }

    /** @test */
    public function test_step_method()
    {
        $this->field->step(5);
        $this->assertArrayHasKey('step', $this->field->getAttributes());
        $this->assertEquals(5, $this->field->getAttribute('step'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->step(5));
    }

    /** @test */
    public function test_max_method()
    {
        $this->field->max(10);
        $this->assertArrayHasKey('max', $this->field->getAttributes());
        $this->assertEquals(10, $this->field->getAttribute('max'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->max(1));
    }

    /** @test */
    public function test_min_method()
    {
        $this->field->min(5);
        $this->assertArrayHasKey('min', $this->field->getAttributes());
        $this->assertEquals(5, $this->field->getAttribute('min'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->min(1));
    }
}
