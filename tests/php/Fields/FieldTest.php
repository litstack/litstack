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

        $this->assertArrayHasKey('trait_default', $field->defaultAttributes);
        $this->assertContains('trait_available', $field->availableAttributes);
        $this->assertContains('trait_required', $field->requiredAttributes);
    }

    /** @test */
    public function it_sets_default_attributes()
    {
        $field = $this->getField(DummyField::class);
        $field->defaultAttributes = ['default' => 'value'];

        $this->callUnaccessibleMethod($field, 'setDefaultAttributes');
        $attributes = $field->getAttributes();
        $this->assertArrayHasKey('default', $attributes);
        $this->assertEquals('value', $attributes['default']);
    }

    /** @test */
    public function it_sets_trait_attributes()
    {
        $field = $this->getField(DummyField::class);

        $this->assertArrayHasKey('dummyTraitAttribute', $field->getAttributes());
    }

    /** @test */
    public function it_initializes_field_extensions()
    {
        Form::extendField(DummyFieldExtension::class, DummyField::class);
        $field = $this->getField(DummyField::class);

        $extensions = $field->getExtensions();

        $this->assertCount(1, $extensions);
        $this->assertIsNotString($extensions[0]);
        $this->assertInstanceof(DummyFieldExtension::class, $extensions[0]);
    }

    /** @test */
    public function it_merges_extensions_properties()
    {
        Form::extendField(DummyFieldExtension::class, DummyField::class);
        $field = $this->getField(DummyField::class);

        $defaultAttributes = $field->getDefaultAttributes();
        $this->assertArrayHasKey('extension_default', $defaultAttributes);

        $availableAttributes = $field->getAvailableAttributes();
        $this->assertContains('extension_available', $availableAttributes);

        $requiredAttributes = $field->getRequiredAttributes();
        $this->assertContains('extension_required', $requiredAttributes);
    }

    /** @test */
    public function test_checkComplete_method()
    {
        $field = $this->getField(DummyField::class);
        $field->requiredAttributes = ['title'];

        $this->expectException(\Fjord\Crud\Exceptions\MissingAttributeException::class);
        $field->checkComplete();

        $field->setAttribute('title', 'something');
        $this->assertTrue($field->checkComplete());
    }

    /** @test */
    public function test_isAttributeAvailable_method()
    {
        $field = $this->getField(DummyField::class);
        $field->availableAttributes = ['title'];

        $this->assertTrue($field->isAttributeAvailable('title'));
        $this->assertFalse($field->isAttributeAvailable('other'));
    }

    /** @test */
    public function it_sets_attribute_when_available_attribute_is_called_as_method()
    {
        $field = $this->getField(DummyField::class);
        $field->availableAttributes = ['title'];

        $field->title('something');
        $this->assertEquals($field->getAttribute('title'), 'something');
    }

    /** @test */
    public function it_fails_when_attribute_is_not_available()
    {
        $field = $this->getField(DummyField::class);
        $field->availableAttributes = [];

        $this->expectException(\Fjord\Exceptions\MethodNotFoundException::class);
        $field->something();
    }

    /** @test */
    public function it_forwards_call_to_extension()
    {
        Form::extendField(DummyFieldExtension::class, DummyField::class);
        $field = $this->getField(DummyField::class);

        $this->assertEquals('called', $field->extensionMethod());

        $this->expectException(\Fjord\Exceptions\MethodNotFoundException::class);
        $field->something();
    }
}

trait DummyFieldTrait
{
    public $requiredTraitAttributes = ['trait_required'];
    public $availableTraitAttributes = ['trait_available'];
    public $defaultTraitAttributes = ['trait_default' => 'value'];

    public function setDummyTraitAttributeAttribute()
    {
        return 'some_value';
    }
}

class DummyField extends Field
{
    use DummyFieldTrait;

    public $availableAttributes = [
        'available'
    ];

    public $defaultAttributes = [
        'default' => 'value'
    ];
}

class DummyFieldExtension
{
    public $requiredAttributes = ['extension_required'];
    public $availableAttributes = ['extension_available'];
    public $defaultAttributes = ['extension_default' => 'value'];

    public function extensionMethod()
    {
        return 'called';
    }
}
