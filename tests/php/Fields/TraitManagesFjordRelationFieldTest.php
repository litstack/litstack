<?php

namespace Tests\Fields;

use Ignite\Crud\Fields\Relations\Concerns\ManagesLitRelationField;
use Ignite\Crud\Fields\Relations\LaravelRelationField;
use Tests\BackendTestCase;
use Tests\Traits\InteractsWithConfig;
use Tests\Traits\InteractsWithFields;
use Illuminate\Database\Eloquent\Model;

class TraitManagesLitRelationFieldTest extends BackendTestCase
{
    use InteractsWithFields;
    use InteractsWithConfig;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(
            ManagesLitRelationFieldField::class,
            'dummy_relation',
            ManagesLitRelationFieldModel::class
        );
    }

    public function getConfig(string $key, ...$params)
    {
        return new ManagesLitRelationFieldConfig();
    }

    /** @test */
    public function test_model_method_sets_model_attribute()
    {
        $this->field->model(ManagesLitRelationFieldRelation::class);
        $this->assertEquals(ManagesLitRelationFieldRelation::class, $this->field->getAttribute('model'));
    }

    /** @test */
    public function test_model_method_sets_model_query()
    {
        $this->field->model(ManagesLitRelationFieldRelation::class);
        $this->assertEquals(ManagesLitRelationFieldRelation::query(), $this->getUnaccessibleProperty($this->field, 'query'));
    }
}

class ManagesLitRelationFieldConfig
{
    public $names = ['singular' => ''];
    public $search = '';
    public $route_prefix = '';
    public $index = null;
    public $model = ManagesLitRelationFieldRelation::class;
}

class ManagesLitRelationFieldField extends LaravelRelationField
{
    use ManagesLitRelationField;

    public function setOrderDefaults()
    {
    }
}

class ManagesLitRelationFieldModel extends Model
{
}

class ManagesLitRelationFieldRelation extends Model
{
}
