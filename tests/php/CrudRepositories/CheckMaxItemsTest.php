<?php

namespace Tests\CrudRepositories;

use Lit\Crud\Field;
use Lit\Crud\Fields\Relations\ManyRelation;
use Lit\Crud\Repositories\Relations\Concerns\ManagesRelated;
use Tests\BackendTestCase;
use Mockery as m;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CheckMaxItemsTest extends BackendTestCase
{
    /** @test */
    public function test_checkMax_items_with_no_maxItems_set()
    {
        $field = m::mock(Field::class)->makePartial();
        $repository = new MaxItemsCheckRepository;
        $repository->field = $field;
        $model = m::mock('model');

        $this->callUnaccessibleMethod($repository, 'checkMaxItems', [$model]);
    }

    /** @test */
    public function test_checkMax_items_not_reached()
    {
        $field = m::mock(ManyRelation::class)->makePartial();
        $field->maxItems(5);
        $model = m::mock('model');
        $builder = m::mock('builder');
        $builder->shouldReceive('count')->andReturn(1);
        $field->shouldReceive('getRelationQuery')->withArgs([$model])->andReturn($builder);
        $repository = new MaxItemsCheckRepository;
        $repository->field = $field;

        $this->callUnaccessibleMethod($repository, 'checkMaxItems', [$model]);
    }

    /** @test */
    public function test_checkMax_items_reached()
    {
        $field = m::mock(ManyRelation::class)->makePartial();
        $field->maxItems(5);
        $model = m::mock('model');
        $builder = m::mock('builder');
        $builder->shouldReceive('count')->andReturn(5);
        $field->shouldReceive('getRelationQuery')->withArgs([$model])->andReturn($builder);
        $repository = new MaxItemsCheckRepository;
        $repository->field = $field;

        $this->expectException(HttpException::class);
        $this->callUnaccessibleMethod($repository, 'checkMaxItems', [$model]);
    }
}

class MaxItemsCheckRepository
{
    use ManagesRelated;
}
