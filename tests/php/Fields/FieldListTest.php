<?php

namespace Tests\Fields;

use Ignite\Crud\BaseForm;
use Ignite\Crud\Fields\ListField\ListField;
use Ignite\Crud\RelationField;
use Illuminate\Database\Eloquent\Model;
use Mockery as m;
use Tests\BackendTestCase;
use Tests\Traits\InteractsWithFields;

class FieldListTest extends BackendTestCase
{
    use InteractsWithFields;

    public function setUp(): void
    {
        parent::setUp();

        $this->field = $this->getField(ListField::class);
    }

    /** @test */
    public function it_is_a_relation_field()
    {
        $this->assertInstanceOf(RelationField::class, $this->field);
    }

    /** @test */
    public function test_maxDepth_method()
    {
        $this->field->maxDepth(5);

        $this->assertEquals(5, $this->field->maxDepth);

        $this->assertEquals($this->field, $this->field->maxDepth(5));
    }

    /** @test */
    public function test_previewTitle_method()
    {
        $this->field->previewTitle('{title}');

        $this->assertEquals('{title}', $this->field->previewTitle);

        $this->assertEquals($this->field, $this->field->previewTitle(''));
    }

    /** @test */
    public function test_form_method_returns_self()
    {
        $result = $this->field->form(function ($form) {
        });

        $this->assertEquals($this->field, $result);
    }

    /** @test */
    public function test_form_method_passes_base_form_to_closure()
    {
        $this->field->form(function ($form) {
            $this->assertInstanceOf(BaseForm::class, $form);
        });
    }

    /** @test */
    public function test_form_method_adds_list_to_form_route_prefix()
    {
        $this->field->form(function ($form) {
            $this->assertStringEndsWith('/list', $form->getRoutePrefix());
        });
    }

    /** @test */
    public function test_getRelationQuery_method()
    {
        $model = m::mock(Model::class);
        $relation = m::mock('relation');
        $model->shouldReceive('dummy_field')->andReturn($relation);

        $result = $this->field->getRelationQuery($model);

        $this->assertEquals($relation, $result);
    }
}
