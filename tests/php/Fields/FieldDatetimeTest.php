<?php

namespace Tests\Fields;

use Carbon\CarbonInterface;
use Ignite\Crud\BaseField;
use Ignite\Crud\Fields\DateTime\DateTime;
use Tests\BackendTestCase;
use Tests\Traits\InteractsWithFields;

class FieldDatetimeTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(DateTime::class);
    }

    /** @test */
    public function it_is_base_field()
    {
        $this->assertInstanceOf(BaseField::class, $this->field);
    }

    /** @test */
    public function test_value_cast_returns_carbon_instance()
    {
        $this->assertInstanceof(CarbonInterface::class, $this->field->castValue('00-00-00'));
    }

    /** @test */
    public function test_inline_method()
    {
        $this->field->inline();
        $this->assertTrue($this->field->getAttribute('inline'));

        $this->field->inline(false);
        $this->assertFalse($this->field->getAttribute('inline'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->inline());
    }

    /** @test */
    public function test_minute_interval_method()
    {
        $this->field->minuteInterval(15);
        $this->assertArrayHasKey('minute_interval', $this->field->getAttributes());
        $this->assertEquals(15, $this->field->getAttribute('minute_interval'));

        $this->field->minuteInterval();
        $this->assertEquals(1, $this->field->getAttribute('minute_interval'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->minuteInterval());
    }

    /** @test */
    // public function test_disabled_hours_method()
    // {
    //     $this->field->disabledHours(['00', '01']);
    //     $this->assertArrayHasKey('disabled_hours', $this->field->getAttributes());
    //     $this->assertEquals(['00', '01'], $this->field->getAttribute('disabled_hours'));

    //     $this->field->disabledHours();
    //     $this->assertEquals([], $this->field->getAttribute('disabled_hours'));

    //     // Assert method returns field instance.
    //     $this->assertEquals($this->field, $this->field->disabledHours());
    // }
}
