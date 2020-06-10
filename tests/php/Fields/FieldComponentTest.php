<?php

namespace FjordTest\Fields;

use Fjord\Crud\Fields\Component;
use FjordTest\BackendTestCase;
use FjordTest\Traits\InteractsWithFields;

class FieldComponentTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(Component::class, 'dummy-component');
    }

    /** @test */
    public function it_sets_component()
    {
        $component = component('dummy-component');
        $this->assertInstanceOf(\Fjord\Vue\Component::class, $this->field->getComponent());
        $this->assertEquals($component, $this->field->getComponent());
    }
}
