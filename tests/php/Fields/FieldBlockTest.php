<?php

namespace FjordTest\Fields;

use Fjord\Crud\Fields\Block\Block;
use Fjord\Crud\Fields\Block\Repeatables;
use Fjord\Crud\Fields\Traits\HasBaseField;
use FjordTest\BackendTestCase;
use FjordTest\Traits\InteractsWithFields;

class FieldBlockTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(Block::class);
    }

    /** @test */
    public function it_has_base_field()
    {
        $this->assertHasTrait(HasBaseField::class, $this->field);
    }

    /** @test */
    public function test_hasRepeatable_method()
    {
        $this->field->repeatables(function ($rep) {
            $rep->add('text', function () {
            });
        });

        $this->assertTrue($this->field->hasRepeatable('text'));
        $this->assertFalse($this->field->hasRepeatable('other'));
    }

    /** @test */
    public function test_getRepeatable_method()
    {
        $this->field->repeatables(function ($rep) {
            $rep->add('text', function () {
            });
        });

        $this->assertNotNull($this->field->getRepeatable('text'));
        $this->assertNull($this->field->getRepeatable('other'));
    }

    /** @test */
    public function it_sets_order_column()
    {
        $this->assertArrayHasKey('orderColumn', $this->field->getAttributes());
        $this->assertEquals('order_column', $this->field->getAttribute('orderColumn'));
    }

    /** @test */
    public function test_blockWidth_method()
    {
        $this->field->blockWidth(11);
        $this->assertArrayHasKey('blockWidth', $this->field->getAttributes());
        $this->assertEquals(11, $this->field->getAttribute('blockWidth'));

        $this->field->blockWidth(1 / 2);
        $this->assertEquals(1 / 2, $this->field->getAttribute('blockWidth'));

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->blockWidth(11));
    }

    /** @test */
    public function test_repeatables_method()
    {
        $this->field->repeatables(function ($rep) {
            $this->assertInstanceOf(Repeatables::class, $rep);
        });

        // Assert method returns field instance.
        $this->assertEquals($this->field, $this->field->repeatables(function () {
        }));
    }
}
