<?php

namespace Tests\Fields;

use Ignite\Crud\BaseField;
use Ignite\Crud\Fields\Icon;
use Ignite\Foundation\Litstack;
use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use PHPUnit\Framework\TestCase;
use Tests\Traits\InteractsWithFields;

class FieldIconTest extends TestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        $app = new Application;
        $app['lit'] = new Litstack($app);
        Container::setInstance($app);
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
