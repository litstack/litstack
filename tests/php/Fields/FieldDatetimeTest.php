<?php

namespace Tests\Fields;

use Carbon\CarbonInterface;
use Lit\Crud\BaseField;
use Lit\Crud\Fields\Datetime;
use Tests\BackendTestCase;
use Tests\Traits\InteractsWithFields;

class FieldDatetimeTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(Datetime::class);
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
    public function test_onlyDate_method()
    {
        $this->field->onlyDate();
        $this->assertTrue($this->field->getAttribute('only_date'));

        $this->field->onlyDate(false);
        $this->assertFalse($this->field->getAttribute('only_date'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->onlyDate());
    }

    /** @test */
    public function test_formatted_method()
    {
        $this->field->formatted('dummy_format');
        $this->assertArrayHasKey('formatted', $this->field->getAttributes());
        $this->assertEquals('dummy_format', $this->field->getAttribute('formatted'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->formatted(''));
    }
}
