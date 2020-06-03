<?php

namespace FjordTest\Fields;

use Fjord\Crud\Field;
use FjordTest\BackendTestCase;
use FjordTest\Traits\InteractsWithFields;
use Fjord\Crud\Fields\Traits\HasBaseField;

class TraitHasBaseFieldTest extends BackendTestCase
{
    use InteractsWithFields;

    /** @test */
    public function it_can_have_a_hint_per_default()
    {
        $field = $this->getField(HasBaseFieldFieldField::class);
        $field->hint('dummy hint');

        $this->assertEquals('dummy hint', $field->getAttribute('hint'));
    }

    /** @test */
    public function field_without_hint_throws_exception_when_hint_is_called()
    {
        $field = $this->getField(HasBaseFieldFieldWithoutHint::class);

        $this->expectException(\InvalidArgumentException::class);
        $field->hint('dummy hint');
    }

    /** @test */
    public function test_getTitle_returns_title_attribute()
    {
        $field = $this->getField(HasBaseFieldFieldField::class);
        $field->setAttribute('title', 'my dummy title');

        $this->assertEquals('my dummy title', $field->getTitle());
    }
}

class HasBaseFieldFieldField extends Field
{
    use HasBaseField;
}

class HasBaseFieldFieldWithoutHint extends Field
{
    use HasBaseField;

    protected $withoutHint = true;
}
