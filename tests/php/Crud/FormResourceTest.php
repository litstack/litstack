<?php

namespace Tests\Crud;

use Ignite\Crud\FormResource;
use Ignite\Crud\Models\LitFormModel;
use Ignite\Crud\Models\Repeatable;
use Mockery as m;
use Tests\BackendTestCase;
use Tests\Crud\Fixtures\DummyLitFormModel;

class FormResourceTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        DummyLitFormModel::schemaUp();
    }

    public function tearDown(): void
    {
        DummyLitFormModel::schemaDown();
        parent::tearDown();
    }

    /** @test */
    public function it_renders_block_data()
    {
        $model = new DummyLitFormModel([
            'config_type' => Fixtures\ConfigWithBlockField::class,
        ]);
        $model->save();

        $repeatable = new Repeatable();
        $repeatable->type = 'text';
        $repeatable->model_type = get_class($model);
        $repeatable->model_id = $model->id;
        $repeatable->field_id = 'content';
        $repeatable->config_type = Fixtures\ConfigWithBlockField::class;
        $repeatable->form_type = 'show';
        $repeatable->value = ['text' => 'foo'];
        $repeatable->order_column = 0;
        $repeatable->save();

        $this->assertEquals(
            [
                'id'      => $model->id,
                'content' => [
                    ['id' => $repeatable->id, 'text' => 'foo'],
                ],
            ],
            $model->resource()->toArray(request())
        );
    }

    /** @test */
    public function it_renders_fields_data()
    {
        $model = new DummyLitFormModel([
            'config_type' => Fixtures\ConfigWithTwoInputFormFields::class,
        ]);
        $model->save();
        $model->update(['foo' => 'fooo', 'bar' => 'barr']);

        $this->assertEquals(
            ['id' => $model->id, 'foo' => 'fooo', 'bar' => 'barr'],
            $model->resource()->toArray(request())
        );
    }

    /** @test */
    public function test_only_returns_all_field_ids_if_not_set()
    {
        $model = m::mock(LitFormModel::class);
        $model->shouldReceive('getFieldIds')->andReturn(['foo', 'bar', 'baz']);

        $resource = new FormResource($model);
        $this->assertEquals(['foo', 'bar', 'baz'], $resource->getOnly());
    }

    /** @test */
    public function it_adds_except()
    {
        $model = m::mock(LitFormModel::class);
        $model->shouldReceive('getFieldIds')->andReturn(['foo', 'bar', 'baz']);

        $resource = new FormResource($model);
        $resource->except('foo');
        $this->assertEquals(['foo'], $resource->getExcept());
        $this->assertEquals(['bar', 'baz'], $resource->getOnly());

        $resource->except('foo', 'bar');
        $this->assertEquals(['foo', 'bar'], $resource->getExcept());
        $this->assertEquals(['baz'], $resource->getOnly());

        $resource->except(['bar']);
        $this->assertEquals(['bar'], $resource->getExcept());
        $this->assertEquals(['foo', 'baz'], $resource->getOnly());
    }

    /** @test */
    public function it_adds_only()
    {
        $model = m::mock(LitFormModel::class);
        $model->shouldReceive('getFieldIds')->andReturn(['foo', 'bar', 'baz']);

        $resource = new FormResource($model);
        $resource->only('foo');
        $this->assertEquals(['bar', 'baz'], $resource->getExcept());
        $this->assertEquals(['foo'], $resource->getOnly());

        $resource->only('foo', 'bar');
        $this->assertEquals(['baz'], $resource->getExcept());
        $this->assertEquals(['foo', 'bar'], $resource->getOnly());

        $resource->only(['bar']);
        $this->assertEquals(['foo', 'baz'], $resource->getExcept());
        $this->assertEquals(['bar'], $resource->getOnly());
    }
}
