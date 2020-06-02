<?php

namespace FjordTest\Fields;

use Fjord\Crud\BaseField;
use FjordTest\BackendTestCase;
use FjordTest\Traits\InteractsWithFields;

class BaseFieldTest extends BackendTestCase
{
    use InteractsWithFields;

    /** @test */
    public function it_can_have_a_hint_per_default()
    {
        $field = $this->getField(FormItemWrapperField::class);
        $field->hint('dummy hint');

        $this->assertEquals('dummy hint', $field->getAttribute('hint'));
    }

    /** @test */
    public function field_without_hint_throws_exception_when_hint_is_called()
    {
        $field = $this->getField(FormItemWrapperFieldWithoutHint::class);

        $this->expectException(\InvalidArgumentException::class);
        $field->hint('dummy hint');
    }
}

class FormItemWrapperField extends BaseField
{
}

class FormItemWrapperFieldWithoutHint extends BaseField
{
    protected $withoutHint = true;
}
