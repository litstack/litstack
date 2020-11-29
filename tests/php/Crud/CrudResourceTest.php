<?php

namespace Tests\Crud;

use Ignite\Crud\CrudResource;
use Ignite\Crud\Models\LitFormModel;
use Mockery as m;
use Tests\BackendTestCase;
use Tests\Crud\Fixtures\DummyLitFormModel;

class CrudResourceTest extends BackendTestCase
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
    public function it_renders_field_data()
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

        $resource = new CrudResource($model);
        $this->assertEquals(['foo', 'bar', 'baz'], $resource->getOnly());
    }

    /** @test */
    public function it_adds_except()
    {
        $model = m::mock(LitFormModel::class);
        $model->shouldReceive('getFieldIds')->andReturn(['foo', 'bar', 'baz']);

        $resource = new CrudResource($model);
        $resource->except('foo');
        $this->assertEquals(['foo'], $resource->getExcept());
        $this->assertEquals(['bar', 'baz'], $resource->getOnly());

        $resource = new CrudResource($model);
        $resource->except(['foo', 'bar']);
        $this->assertEquals(['foo', 'bar'], $resource->getExcept());
        $this->assertEquals(['baz'], $resource->getOnly());

        $resource = new CrudResource($model);
        $resource->except('foo', 'bar');
        $this->assertEquals(['foo', 'bar'], $resource->getExcept());
        $this->assertEquals(['baz'], $resource->getOnly());
    }

    /** @test */
    public function it_adds_only()
    {
        $model = m::mock(LitFormModel::class);
        $model->shouldReceive('getFieldIds')->andReturn(['foo', 'bar', 'baz']);

        $resource = new CrudResource($model);
        $resource->only('foo');
        $this->assertEquals(['bar', 'baz'], $resource->getExcept());
        $this->assertEquals(['foo'], $resource->getOnly());

        $resource = new CrudResource($model);
        $resource->only(['foo', 'bar']);
        $this->assertEquals(['baz'], $resource->getExcept());
        $this->assertEquals(['foo', 'bar'], $resource->getOnly());

        $resource = new CrudResource($model);
        $resource->only('foo', 'bar');
        $this->assertEquals(['baz'], $resource->getExcept());
        $this->assertEquals(['foo', 'bar'], $resource->getOnly());
    }
}
