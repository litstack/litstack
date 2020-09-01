<?php

namespace Tests\Fields;

use Ignite\Crud\Fields\Component;
use Tests\BackendTestCase;
use Tests\Traits\InteractsWithFields;

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
        $this->assertInstanceOf(\Ignite\Vue\Component::class, $this->field->getComponent());
        $this->assertEquals($component, $this->field->getComponent());
    }
}
