<?php

namespace FjordTest\Fields;

use Carbon\CarbonInterface;
use Fjord\Crud\BaseField;
use Fjord\Crud\Fields\Datetime;
use FjordTest\Traits\InteractsWithFields;
use PHPUnit\Framework\TestCase;

class FieldDatetimeTest extends TestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        $this->field = $this->getField(Datetime::class);
    }

    /** @test */
    public function it_is_base_field()
    {
        $this->assertInstanceOf(BaseField::class, $this->field);
    }

    /** @test */
    public function test_cast_returns_carbon_instance()
    {
        $this->assertInstanceof(CarbonInterface::class, $this->field->cast('00-00-00'));
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
