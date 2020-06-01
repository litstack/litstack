<?php

namespace FjordTest\Fields;

use Fjord\Crud\Field;
use FjordTest\BackendTestCase;
use Fjord\Support\Facades\Form;
use Illuminate\Database\Eloquent\Model;
use FjordTest\Traits\InteractsWithFields;
use Fjord\Crud\Fields\Relations\Concerns\ManagesFjordRelationField;
use Fjord\Crud\Fields\Relations\LaravelRelationField;
use FjordTest\Traits\InteractsWithConfig;

class TraitManagesFjordRelationFieldTest extends BackendTestCase
{
    use InteractsWithFields, InteractsWithConfig;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(
            ManagesFjordRelationFieldField::class,
            'dummy_relation',
            ManagesFjordRelationFieldModel::class
        );
    }

    public function getConfig(string $key, ...$params)
    {
        return new ManagesFjordRelationFieldConfig;
    }

    /** @test */
    public function test_model_method_sets_model_attribute()
    {
        $this->field->model(ManagesFjordRelationFieldRelation::class);
        $this->assertEquals(ManagesFjordRelationFieldRelation::class, $this->field->getAttribute('model'));
    }

    /** @test */
    public function test_model_method_loads_related_config()
    {
        $this->field->model(ManagesFjordRelationFieldRelation::class);
        $this->assertArrayHasKey('config', $this->field->getAttributes());
    }

    /** @test */
    public function test_model_method_sets_model_query()
    {
        $this->field->model(ManagesFjordRelationFieldRelation::class);
        $this->assertEquals(ManagesFjordRelationFieldRelation::query(), $this->getUnaccessibleProperty($this->field, 'query'));
    }
}

class ManagesFjordRelationFieldConfig
{
    public $names = ['singular' => ''];
    public $search = '';
    public $route_prefix = '';
    public $index = '';
}

class ManagesFjordRelationFieldField extends LaravelRelationField
{
    use ManagesFjordRelationField;

    public function setOrderDefaults()
    {
    }
}

class ManagesFjordRelationFieldModel extends Model
{
}

class ManagesFjordRelationFieldRelation extends Model
{
}
