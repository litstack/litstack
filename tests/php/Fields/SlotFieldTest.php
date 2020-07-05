<?php

namespace FjordTest\Fields;

use Fjord\Crud\Field;
use FjordTest\BackendTestCase;
use FjordTest\Traits\InteractsWithFields;

class SlotFieldTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(SlotField::class);
    }

    /** @test */
    public function test_getSlotMethodName_method()
    {
        $slotName = 'dummy';
        $methodName = $this->callUnaccessibleMethod($this->field, 'getSlotMethodName', [$slotName]);
        $this->assertEquals('dummySlot', $methodName);

        $slotName = 'dummy_slot_with_snake_case_name';
        $methodName = $this->callUnaccessibleMethod($this->field, 'getSlotMethodName', [$slotName]);
        $this->assertEquals('dummySlotWithSnakeCaseNameSlot', $methodName);
    }

    /** @test */
    public function test_slotExists_method()
    {
        $this->assertTrue($this->field->slotExists('dummy'));
        $this->assertFalse($this->field->slotExists('some_other_slot'));
    }

    /** @test */
    public function test_slot_method_adds_component_to_slots_array()
    {
        $this->field->slot('dummy', 'dummy-component');

        $this->assertArrayHasKey('dummy', $this->field->getAttribute('slots'));
        $this->assertEquals('dummy-component', $this->field->getAttribute('slots')['dummy']);
    }
}

class SlotField extends Field
{
    protected function dummySlot($component)
    {
    }
}
