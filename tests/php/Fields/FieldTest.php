<?php

namespace FjordTest\Fields;

use Fjord\Crud\Field;
use FjordTest\BackendTestCase;
use Fjord\Support\Facades\Form;
use FjordTest\Traits\InteractsWithFields;
use Illuminate\Database\Eloquent\Model;

class FieldTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_merges_trait_attributes()
    {
        $field = $this->getField(DummyField::class);

        $this->assertContains('trait_required', $field->required);
    }

    /** @test */
    public function it_sets_default_attributes()
    {
        $field = $this->getField(DummyField::class);

        $attributes = $field->getAttributes();

        $this->assertArrayHasKey('dummyTraitAttribute', $attributes);
        $this->assertEquals('some_value', $attributes['dummyTraitAttribute']);
    }

    /** @test */
    public function it_sets_default_attribute_from_method()
    {
        $field = $this->getField(DummyField::class);

        $attributes = $field->getAttributes();

        $this->assertArrayHasKey('default', $attributes);
        $this->assertEquals('value', $attributes['default']);
    }

    /** @test */
    public function test_checkComplete_method()
    {
        $field = $this->getField(DummyField::class);
        $field->required = ['title'];

        $this->expectException(\Fjord\Crud\Exceptions\MissingAttributeException::class);
        $field->checkComplete();

        $field->setAttribute('title', 'something');
        $this->assertTrue($field->checkComplete());
    }

    /** @test */
    public function it_fails_when_attribute_is_not_available()
    {
        $field = $this->getField(DummyField::class);

        $this->expectException(\Fjord\Exceptions\MethodNotFoundException::class);
        $field->something();
    }
}

trait DummyFieldTrait
{
    public $traitRequired = ['trait_required'];

    public function setDummyTraitAttributeDefault()
    {
        return 'some_value';
    }
}

class DummyField extends Field
{
    use DummyFieldTrait;

    public function setDefaultAttributes()
    {
        $this->setAttribute('default', 'value');
    }
}
