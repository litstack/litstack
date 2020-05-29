<?php

namespace FjordTest\Fields;

use Fjord\Crud\Fields\Icon;
use FjordTest\BackendTestCase;
use FjordTest\Traits\InteractsWithFields;
use Fjord\Crud\Fields\Concerns\FieldHasRules;
use Fjord\Crud\Fields\Concerns\FormItemWrapper;
use Fjord\Crud\Fields\Concerns\TranslatableField;

class FieldIconTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(Icon::class);
    }

    /** @test */
    public function it_has_form_item_wrapper()
    {
        $this->assertHasTrait(FormItemWrapper::class, $this->field);
    }

    /** @test */
    public function it_has_default_icons()
    {
        $this->assertGreaterThan(0, count($this->field->icons));
    }
}
