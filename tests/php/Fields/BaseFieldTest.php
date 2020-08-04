<?php

namespace FjordTest\Fields;

use Fjord\Crud\BaseField;
use Fjord\Crud\Fields\Traits\HasBaseField;
use FjordTest\Traits\InteractsWithFields;
use FjordTest\Traits\TestHelpers;
use PHPUnit\Framework\TestCase;

class BaseFieldTest extends TestCase
{
    use InteractsWithFields, TestHelpers;

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
