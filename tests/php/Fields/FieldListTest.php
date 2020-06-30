<?php

namespace FjordTest\Fields;

use Fjord\Crud\BaseField;
use Fjord\Crud\Fields\Icon;
use FjordTest\BackendTestCase;
use FjordTest\Traits\InteractsWithFields;

class FieldListTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(Icon::class);
    }

    /** @test */
    public function it_is_base_field()
    {
        $this->assertInstanceOf(BaseField::class, $this->field);
    }

    /** @test */
    public function it_has_multiple_default_icons()
    {
        $this->assertGreaterThan(1, count($this->field->icons));
    }

    /** @test */
    public function test_search_method()
    {
        $this->field->search();
        $this->assertTrue($this->field->getAttribute('search'));

        $this->field->search(false);
        $this->assertFalse($this->field->getAttribute('search'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->search());
    }

    /** @test */
    public function test_icons_method()
    {
        $this->field->icons(['dummy_icon']);
        $this->assertArrayHasKey('icons', $this->field->getAttributes());
        $this->assertEquals(['dummy_icon'], $this->field->getAttribute('icons'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->icons([]));
    }
}
