<?php

namespace Tests\Fields;

use Ignite\Crud\Fields\Component;
use PHPUnit\Framework\TestCase;
use Tests\Traits\InteractsWithComponents;
use Tests\Traits\InteractsWithFields;

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
        $this->assertInstanceOf(\Ignite\Vue\Component::class, $this->field->getComponent());
        $this->assertEquals($component, $this->field->getComponent());
    }
}
