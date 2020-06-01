<?php

namespace FjordTest\Fields;

use Fjord\Crud\Field;
use FjordTest\BackendTestCase;
use Fjord\User\Models\FjordUser;
use Illuminate\Support\Facades\Auth;
use FjordTest\Traits\InteractsWithFields;

class FieldTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();
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

        $this->expectException(\Fjord\Crud\Exceptions\MissingAttributeException::class);
        $field->checkComplete();

        $field->setAttribute('title', 'something');
        $this->assertTrue($field->checkComplete());
    }

    /** @test */
    public function it_fails_when_attribute_is_not_available()
    {
        $field = $this->getField(DummyField::class);

        $this->expectException(\Fjord\Exceptions\MethodNotFoundException::class);
        $field->something();
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
    public function test_authorized_passes_logged_in_fjord_user_to_closure()
    {
        $field = $this->getField(DummyField::class);

        $fjordUser = factory(FjordUser::class)->create([
            'username' => 'dummy_fjord_user',
        ]);

        Auth::guard('fjord')->login($fjordUser);

        $field->authorize(function ($user) use ($fjordUser) {
            $this->assertInstanceOf(FjordUser::class, $user);
            $this->assertEquals($user, $fjordUser);
        });
        $field->authorized();
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

    public function setDefaultAttributes()
    {
        $this->setAttribute('default', 'value');
    }
}
