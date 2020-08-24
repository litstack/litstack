<?php

namespace Tests\Fields;

use Lit\Crud\BaseField;
use Lit\Crud\Fields\Input;
use Lit\Crud\Fields\Traits\FieldHasRules;
use Lit\Crud\Fields\Traits\TranslatableField;
use Tests\Traits\InteractsWithFields;
use Tests\Traits\TestHelpers;
use PHPUnit\Framework\TestCase;

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
