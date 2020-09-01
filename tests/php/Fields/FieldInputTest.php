<?php

namespace Tests\Fields;

use Ignite\Crud\BaseField;
use Ignite\Crud\Fields\Input;
use Ignite\Crud\Fields\Traits\FieldHasRules;
use Ignite\Crud\Fields\Traits\TranslatableField;
use PHPUnit\Framework\TestCase;
use Tests\Traits\InteractsWithFields;
use Tests\Traits\TestHelpers;

class FieldInputTest extends TestCase
{
    use InteractsWithFields, TestHelpers;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(Input::class);
    }

    /** @test */
    public function it_can_have_rules()
    {
        $this->assertHasTrait(FieldHasRules::class, $this->field);
    }

    /** @test */
    public function it_can_be_translatable()
    {
        $this->assertHasTrait(TranslatableField::class, $this->field);
    }

    /** @test */
    public function it_is_base_field()
    {
        $this->assertInstanceOf(BaseField::class, $this->field);
    }
}
