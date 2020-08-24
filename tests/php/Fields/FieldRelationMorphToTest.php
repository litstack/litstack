<?php

namespace Tests\Fields;

use Lit\Crud\BaseForm;
use Lit\Crud\Fields\Relations\MorphToRegistrar;
use Tests\BackendTestCase;
use Tests\Traits\InteractsWithFields;
use Illuminate\Database\Eloquent\Model;

class FieldRelationMorphToTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(
            MorphToRegistrar::class,
            'dummy_morph_to_relation',
            MorphToRegistrarFieldModel::class,
            $this->getForm()
        );
    }

    /** @test */
    public function test_title_method()
    {
        $this->field->title('dummy_title');
        $this->assertArrayHasKey('title', $this->field->getAttributes());
        $this->assertEquals('dummy_title', $this->field->getAttribute('title'));
    }

    /** @test */
    public function it_can_add_morph_type()
    {
        $this->field->morphTypes(function ($morph) {
            $type = $morph->to(MorphToRelationFirst::class);
            $this->assertInstanceOf(\Lit\Crud\Fields\Relations\MorphTo::class, $type);

            $type = $morph->to(MorphToRelationSecond::class);
            $this->assertInstanceOf(\Lit\Crud\Fields\Relations\MorphTo::class, $type);
        });

        $this->assertCount(2, $this->field->getAttribute('morphTypes'));
    }

    /** @test */
    public function test_morph_type_gets_correct_relation_name()
    {
        $this->field->morphTypes(function ($morph) {
            $type = $morph->to(MorphToRelationFirst::class);
            $this->assertEquals('dummy_morph_to_relation', $type->getRelationName());
        });
    }

    /** @test */
    public function test_morph_type_gets_correct_related_model_class()
    {
        $this->field->morphTypes(function ($morph) {
            $type = $morph->to(MorphToRelationFirst::class);
            $this->assertEquals(MorphToRelationFirst::class, $type->getRelatedModelClass());
        });
    }

    /** @test */
    public function test_morph_type_sets_correct_id()
    {
        $this->field->morphTypes(function ($morph) {
            $type = $morph->to(MorphToRelationFirst::class);

            // Expected pattern:
            // {relation_name}-{related_table}
            $this->assertEquals('dummy_morph_to_relation-dummy_relation_table', $type->id);
        });
    }

    /** @test */
    public function test_morph_type_sets_correct_model_class()
    {
        $this->field->morphTypes(function ($morph) {
            $type = $morph->to(MorphToRelationFirst::class);

            $this->assertEquals(MorphToRegistrarFieldModel::class, $type->getModel());
        });
    }

    /** @test */
    public function test_morph_type_can_get_null_result()
    {
        $this->field->morphTypes(function ($morph) {
            $type = $morph->to(MorphToRelationFirst::class);

            $results = $type->getResults(new MorphToRegistrarFieldModel());
            //$this->assertEquals(MorphToRegistrarFieldModel::class, $type->getModel());
        });
    }

    public function getForm()
    {
        return new BaseForm(MorphToRegistrarFieldModel::class);
    }
}

class MorphToRelationFirst extends Model
{
    public $table = 'dummy_relation_table';
}

class MorphToRelationSecond extends Model
{
}

class MorphToRegistrarFieldModel extends Model
{
    public function dummy_morph_to_relation()
    {
        return $this->morphTo();
    }
}
