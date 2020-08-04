<?php

namespace FjordTest\Fields;

use Fjord\Crud\Fields\Component;
use FjordTest\Traits\InteractsWithComponents;
use FjordTest\Traits\InteractsWithFields;
use PHPUnit\Framework\TestCase;

class FieldComponentTest extends TestCase
{
    use InteractsWithFields, InteractsWithComponents;

    public function setUp(): void
    {
        $this->setupApplication();

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
