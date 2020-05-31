<?php

namespace FjordTest\Fields;

use Fjord\Crud\Field;
use FjordTest\BackendTestCase;
use Fjord\Support\Facades\Form;
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
    public function it_sets_default_slot_attribute()
    {
        $attributes = $this->field->getAttributes();
        $this->assertArrayHasKey('slots', $attributes);
        $this->assertIsArray($attributes['slots']);
    }

    /** @test */
    public function it_merges_available_slots()
    {
        $attributes = $this->field->getAttributes();
        $this->assertArrayHasKey('slots', $attributes);
        $this->assertIsArray($attributes['slots']);
    }
}

class SlotField extends Field
{
}
