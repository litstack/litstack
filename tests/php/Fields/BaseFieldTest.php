<?php

namespace Tests\Fields;

use Ignite\Crud\BaseField;
use Ignite\Crud\Fields\Traits\HasBaseField;
use PHPUnit\Framework\TestCase;
use Tests\Traits\InteractsWithFields;
use Tests\Traits\TestHelpers;

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
