<?php

namespace FjordTest;

use Fjord\Crud\Field;
use Fjord\Crud\FieldDependency;
use FjordTest\Traits\TestHelpers;
use InvalidArgumentException;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class FieldDependencyTest extends TestCase
{
    use TestHelpers;

    /** @test */
    public function test_constructor()
    {
        $field = m::mock(Field::class);
        $dependecy = new FieldDependency('when', $field, 'dummy value');

        $this->assertSame('when', $this->getUnaccessibleProperty($dependecy, 'condition'));
        $this->assertSame($field, $this->getUnaccessibleProperty($dependecy, 'dependent'));
        $this->assertSame('dummy value', $this->getUnaccessibleProperty($dependecy, 'value'));
    }

    /** @test */
    public function it_has_conditions()
    {
        $conditions = $this->getUnaccessibleProperty(FieldDependency::class, 'conditions');

        $this->assertContains('when', $conditions);
        $this->assertContains('whenNot', $conditions);
        $this->assertContains('whenContains', $conditions);
    }

    /** @test */
    public function test_make_method()
    {
        $field = m::mock(Field::class);
        $this->assertInstanceOf(
            FieldDependency::class,
            FieldDependency::make('when', $field, 'dummy value')
        );
    }

    /** @test */
    public function it_fails_for_invalid_condition()
    {
        $this->expectException(InvalidArgumentException::class);

        $field = m::mock(Field::class);
        new FieldDependency('other', $field, 'dummy value');
    }

    /** @test */
    public function test_or_conditions_can_be_created()
    {
        $field = m::mock(Field::class);
        new FieldDependency('orWhen', $field, 'dummy value');
    }

    /** @test */
    public function test_render_method()
    {
        $field = m::mock(Field::class);
        $field->shouldReceive('getAttribute')->withArgs(['id']);
        $condition = new FieldDependency('when', $field, 'dummy value');

        $rendered = $condition->render();

        $this->assertArrayHasKey('condition', $rendered);
        $this->assertArrayHasKey('attribute', $rendered);
        $this->assertArrayHasKey('value', $rendered);
    }
}
