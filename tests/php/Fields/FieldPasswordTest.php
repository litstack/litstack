<?php

namespace FjordTest\Fields;

use Fjord\Crud\BaseField;
use Fjord\Crud\Fields\Password;
use Fjord\Crud\Fields\Traits\FieldHasRules;
use FjordTest\BackendTestCase;
use FjordTest\Traits\InteractsWithFields;
use Illuminate\Support\Facades\Hash;
use Mockery as m;

class FieldPasswordTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(Password::class);
    }

    /** @test */
    public function it_is_base_field()
    {
        $this->assertInstanceOf(BaseField::class, $this->field);
    }

    /** @test */
    public function it_can_have_rules()
    {
        $this->assertHasTrait(FieldHasRules::class, $this->field);
    }

    /** @test */
    public function it_hashes_password()
    {
        $formatted = $this->field->format('secret');
        $this->assertTrue(Hash::check('secret', $formatted));
    }

    /** @test */
    public function it_fills_password_to_model_by_default()
    {
        $model = m::mock('model');
        $model->password = 'none';

        $this->field->fillModel($model, 'password', 'dummy_password');
        $this->assertEquals($model->password, 'dummy_password');
    }

    /** @test */
    public function it_doesnt_fill_to_model_when_it_should_be_used_for_rules_only()
    {
        $model = m::mock('model');
        $model->password = 'none';

        $this->field->rulesOnly();
        $this->assertTrue($this->getUnaccessibleProperty($this->field, 'rulesOnly'));

        $this->field->fillModel($model, 'password', 'dummy_password');
        $this->assertEquals($model->password, 'none');
    }

    /** @test */
    public function test_confirm_method()
    {
        $this->field->confirm();
        $this->assertTrue($this->field->noScore);
        $this->assertTrue($this->getUnaccessibleProperty($this->field, 'rulesOnly'));
        $rules = $this->getUnaccessibleProperty($this->field, 'rules');
        $this->assertCount(2, $rules);
        $this->assertContains('required', $rules);
    }
}
