<?php

namespace FjordTest\Fields;

use Fjord\Crud\Field;
use FjordTest\BackendTestCase;
use FjordTest\Traits\InteractsWithFields;
use Fjord\Crud\Fields\Concerns\FormItemWrapper;

class TraitFormItemWrapperTest extends BackendTestCase
{
    use InteractsWithFields;

    /** @test */
    public function it_can_have_a_hint_per_default()
    {
        $field = $this->getField(FormItemWrapperField::class);
        $availableAttributes = $this->getUnaccessibleProperty($field, 'availableAttributes');
        $this->assertContains('hint', $availableAttributes);
    }

    /** @test */
    public function field_without_hint_cant_hint()
    {
        $field = $this->getField(FormItemWrapperFieldWithoutHint::class);
        $availableAttributes = $this->getUnaccessibleProperty($field, 'availableAttributes');
        $this->assertNotContains('hint', $availableAttributes);
    }
}

class FormItemWrapperField extends Field
{
    use FormItemWrapper;
}

class FormItemWrapperFieldWithoutHint extends Field
{
    use FormItemWrapper;

    protected $withoutHint = true;
}
