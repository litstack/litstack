<?php

namespace Tests\Fields;

use Lit\Crud\BaseForm;
use Lit\Crud\Field;
use Lit\Crud\FieldDependency;
use Lit\Exceptions\Traceable\MissingAttributeException;
use Lit\User\Models\LitUser;
use Tests\BackendTestCase;
use Tests\Traits\InteractsWithFields;
use Illuminate\Support\Facades\Auth;

class FieldTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function test_getTitle_method()
    {
        $field = $this->getField(DummyField::class);

        $this->setUnaccessibleProperty($field, 'attributes', ['id' => 'my_field']);
        $this->assertEquals('My Field', $field->getTitle());

        $this->setUnaccessibleProperty($field, 'attributes', ['id' => 'other']);
        $this->assertEquals('Other', $field->getTitle());
    }

    /** @test */
    public function it_merges_trait_attributes()
    {
        $field = $this->getField(DummyField::class);

        $this->assertContains('trait_required', $field->required);
    }

    /** @test */
    public function it_sets_default_attributes()
    {
        $field = $this->getField(DummyField::class);

        $attributes = $field->getAttributes();

        $this->assertArrayHasKey('dummyTraitAttribute', $attributes);
        $this->assertEquals('some_value', $attributes['dummyTraitAttribute']);
    }

    /** @test */
    public function it_sets_default_attribute_from_method()
    {
        $field = $this->getField(DummyField::class);

        $attributes = $field->getAttributes();

        $this->assertArrayHasKey('default', $attributes);
        $this->assertEquals('value', $attributes['default']);
    }

    /** @test */
    public function test_checkComplete_method()
    {
        $field = $this->getField(DummyField::class);
        $field->required = ['title'];

        $this->expectException(MissingAttributeException::class);
        $field->checkComplete();

        $field->setAttribute('title', 'something');
        $this->assertTrue($field->checkComplete());
    }

    /** @test */
    public function test_readonly_method()
    {
        $field = $this->getField(DummyField::class);

        $field->readonly();
        $this->assertTrue($field->getAttribute('readonly'));

        $field->readonly(false);
        $this->assertFalse($field->getAttribute('readonly'));

        // Assert method returns field instance.
        $this->assertEquals($field, $field->readonly());
    }

    /** @test */
    public function test_authorize_method_sets_authorize_closure()
    {
        $field = $this->getField(DummyField::class);

        $authorizeClosure = function () {
        };

        $field->authorize($authorizeClosure);
        $this->assertEquals($authorizeClosure, $this->getUnaccessibleProperty($field, 'authorize'));
    }

    /** @test */
    public function test_authorized_method()
    {
        $field = $this->getField(DummyField::class);

        $field->authorize(function () {
            return true;
        });
        $this->assertTrue($field->authorized());

        $field->authorize(function () {
            return false;
        });
        $this->assertFalse($field->authorized());
    }

    /** @test */
    public function test_authorized_passes_logged_in_lit_user_to_closure()
    {
        $field = $this->getField(DummyField::class);

        $litUser = factory(LitUser::class)->create([
            'username' => 'dummy_lit_user',
        ]);

        Auth::guard('lit')->login($litUser);

        $field->authorize(function ($user) use ($litUser) {
            $this->assertInstanceOf(LitUser::class, $user);
            $this->assertEquals($user, $litUser);
        });
        $field->authorized();
    }

    /** @test */
    public function test_dependency_using_field_instance()
    {
        $form = new BaseForm('model');
        $dependent = $form->input('dependent');
        $field = $form->input('field');

        $this->assertSame($field, $field->when($dependent, 'dummy-value'));
        $this->assertCount(1, $field->getDependencies());
        $this->assertInstanceOf(FieldDependency::class, $dependency = $field->getDependencies()->first());
        $this->assertSame($dependent, $dependency->getDependent());
    }

    /** @test */
    public function test_dependency_using_field_id()
    {
        $form = new BaseForm('model');
        $dependent = $form->input('dependent');
        $field = $form->input('field');

        $this->assertSame($field, $field->when('dependent', 'dummy-value'));
        $this->assertCount(1, $field->getDependencies());
        $this->assertInstanceOf(FieldDependency::class, $dependency = $field->getDependencies()->first());
        $this->assertSame($dependent, $dependency->getDependent());
    }
}

trait DummyFieldTrait
{
    public $traitRequired = ['trait_required'];

    public function setDummyTraitAttributeDefault()
    {
        return 'some_value';
    }
}

class DummyField extends Field
{
    use DummyFieldTrait;

    public function mount()
    {
        $this->setAttribute('default', 'value');
    }
}
