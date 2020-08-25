<?php

namespace Tests\Fields;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Ignite\Crud\Field;
use Ignite\Crud\Fields\Traits\TranslatableField as TranslatableFieldTrait;
use Ignite\Crud\Models\Traits\Translatable;
use Tests\BackendTestCase;
use Tests\Traits\InteractsWithFields;
use Illuminate\Database\Eloquent\Model;

class TraitTranslatableFieldTest extends BackendTestCase
{
    use InteractsWithFields;

    /** @test */
    public function it_is_not_translatable_by_default()
    {
        $field = $this->getField(TranslatableField::class);
        $this->assertFalse($field->translatable);
    }

    /** @test */
    public function it_notices_translatable_attributes_on_translatable_models()
    {
        $field = $this->getField(
            TranslatableField::class,
            'translated_attribute',
            TranslatableFieldModel::class
        );
        $this->assertTrue($field->translatable);

        $field = $this->getField(
            TranslatableField::class,
            'non_translated_attribute',
            TranslatableFieldModel::class
        );
        $this->assertFalse($field->translatable);
    }

    /** @test */
    public function test_translatable_method()
    {
        $field = $this->getField(TranslatableField::class);
        $field->translatable();
        $this->assertTrue($field->translatable);
        $field->translatable(false);
        $this->assertFalse($field->translatable);
    }
}

class TranslatableField extends Field
{
    use TranslatableFieldTrait;
}

class TranslatableFieldModel extends Model implements TranslatableContract
{
    use Translatable;

    public $translatedAttributes = ['translated_attribute'];
}
