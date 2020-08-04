<?php

namespace FjordTest\Fields;

use Fjord\Crud\BaseField;
use Fjord\Crud\Fields\Select;
use Fjord\Crud\Fields\Traits\FieldHasRules;
use FjordTest\Traits\InteractsWithFields;
use FjordTest\Traits\TestHelpers;
use PHPUnit\Framework\TestCase;

class FieldSelectTest extends TestCase
{
    use InteractsWithFields, TestHelpers;

    public function setUp(): void
    {
        $this->field = $this->getField(Select::class);
    }

    /** @test */
    public function it_is_base_field()
    {
        $this->assertInstanceOf(BaseField::class, $this->field);
    }

    /** @test */
    public function it_can_have_rules()
    {
        $this->assertHasTrait(FieldHasRules::class, $this->field);
    }

    /** @test */
    public function test_options_method()
    {
        $this->field->options(['a' => 'b']);
        $this->assertArrayHasKey('options', $this->field->getAttributes());
        $this->assertEquals(['a' => 'b'], $this->field->getAttribute('options'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->options([]));
    }
}
