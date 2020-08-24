<?php

namespace Tests\Fields;

use Lit\Crud\BaseField;
use Lit\Crud\Fields\Traits\HasBaseField;
use Tests\Traits\InteractsWithFields;
use Tests\Traits\TestHelpers;
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
