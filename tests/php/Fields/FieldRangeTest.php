<?php

namespace FjordTest\Fields;

use Carbon\CarbonInterface;
use Fjord\Crud\Fields\Range;
use FjordTest\BackendTestCase;
use FjordTest\Traits\InteractsWithFields;
use Fjord\Crud\Fields\Concerns\FormItemWrapper;

class FieldRangeTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(Range::class);
    }

    /** @test */
    public function it_has_form_item_wrapper()
    {
        $this->assertHasTrait(FormItemWrapper::class, $this->field);
    }

    /** @test */
    public function test_cast()
    {
        $this->assertIsInt($this->field->cast('1'));
        $this->assertIsInt($this->field->cast('1.5'));
        $this->assertIsInt($this->field->cast('text'));
    }
}
