<?php

namespace FjordTest\Fields;

use Fjord\Crud\Fields\ListField\ListRelation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class FieldListRelationTest extends TestCase
{
    /** @test */
    public function getResults_calls_sort_by_and_unflattenList()
    {
        $collection = m::mock('list_collection');
        $builder = $this->getBuilder();
        $model = $this->getModel();

        $builder->shouldReceive('get')->andReturn($collection);

        $relation = new ListRelation($builder, $model, '', '', 'localKey');

        $collection->shouldReceive('sortBy')->andReturn($collection);
        $collection->shouldReceive('unflattenList')->andReturn($collection);

        $relation->getResults();
    }

    public function getModel()
    {
        $model = m::mock(Model::class)->makePartial();
        $model->shouldReceive('getAttribute')->withArgs(['localKey'])->andReturn(true);

        return $model;
    }

    public function getBuilder()
    {
        $builder = m::mock(Builder::class)->makePartial();
        $builder->shouldReceive('where')->andReturn($builder);
        $builder->shouldReceive('whereNotNull')->andReturn($builder);

        return $builder;
    }
}
