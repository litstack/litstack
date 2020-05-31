<?php

namespace FjordTest\Fields;

use FjordTest\BackendTestCase;
use Fjord\Crud\Fields\Password;
use Illuminate\Support\Facades\Hash;
use FjordTest\Traits\InteractsWithFields;
use Fjord\Crud\Fields\Concerns\FieldHasRules;
use Fjord\Crud\Fields\Concerns\ForceFillable;
use Fjord\Crud\Fields\Concerns\FormItemWrapper;

class FieldPasswordTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(Password::class);
    }

    /** @test */
    public function it_has_form_item_wrapper()
    {
        $this->assertHasTrait(FormItemWrapper::class, $this->field);
    }

    /** @test */
    public function it_can_have_rules()
    {
        $this->assertHasTrait(FieldHasRules::class, $this->field);
    }

    /** @test */
    public function it_can_force_fillable()
    {
        $this->assertHasTrait(ForceFillable::class, $this->field);
    }

    /** @test */
    public function it_hashes_password()
    {
        $formatted = $this->field->format('secret');
        $this->assertTrue(Hash::check('secret', $formatted));
    }

    /** @test */
    public function test_confirm_method()
    {
        $this->field->confirm();
        $this->assertTrue($this->field->noScore);
        $this->assertFalse($this->field->isFillable());
        $rules = $this->getUnaccessibleProperty($this->field, 'rules');
        $this->assertCount(2, $rules);
        $this->assertContains('required', $rules);
    }
}
