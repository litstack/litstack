<?php

namespace FjordTest\Fields;

use Fjord\Crud\BaseField;
use FjordTest\BackendTestCase;
use FjordTest\Traits\InteractsWithFields;
use Fjord\Crud\Fields\Traits\HasBaseField;

class BaseFieldTest extends BackendTestCase
{
    use InteractsWithFields;

    /** @test */
    public function it_has_HasBaseField_trait()
    {
        $field = $this->getField(BaseFieldField::class);

        $this->assertHasTrait(HasBaseField::class, $field);
    }
}

class BaseFieldField extends BaseField
{
}
