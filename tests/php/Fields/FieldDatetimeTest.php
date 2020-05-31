<?php

namespace FjordTest\Fields;

use Carbon\CarbonInterface;
use FjordTest\BackendTestCase;
use Fjord\Crud\Fields\Datetime;
use FjordTest\Traits\InteractsWithFields;
use Fjord\Crud\Fields\Concerns\FieldHasRules;
use Fjord\Crud\Fields\Concerns\FormItemWrapper;

class FieldDatetimeTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(Datetime::class);
    }

    /** @test */
    public function it_has_form_item_wrapper()
    {
        $this->assertHasTrait(FormItemWrapper::class, $this->field);
    }

    /** @test */
    public function test_cast()
    {
        $this->assertInstanceof(CarbonInterface::class, $this->field->cast('00-00-00'));
    }
}
