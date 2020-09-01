<?php

namespace Tests\Crud;

use Ignite\Crud\Models\Relation as RelationModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Mockery as m;
use Tests\BackendTestCase;
use Tests\TestSupport\Models\Post;

class ManyRelationMacroTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->installLit();
        $this->migrate();

        // Setting up form relation.
        $this->model = Post::create([]);
        $this->related1 = Post::create([]);
        $this->related2 = Post::create([]);
    }

    /** @test */
    public function it_returns_null_when_not_relation_doesnt_exist()
    {
        $model = $this->getModel('many_relation');

        $this->assertNull($model->one_relation);
    }

    /** @test */
    public function it_returns_relation_instance_builder_when_called_as_method()
    {
        $model = $this->getModel('many_relation');

        $this->assertInstanceOf(Relation::class, $model->many_relation());
    }

    /** @test */
    public function it_finds_existing_related_models()
    {
        $model = $this->getModel('many_relation');
        $this->createFormRelation('many_relation', 1);

        $this->assertTrue($model->many_relation()->exists());
    }

    /** @test */
    public function it_finds_correct_related_models()
    {
        $model = $this->getModel('many_relation');
        $this->createFormRelation('many_relation', 1);

        $relations = $model->many_relation()->getResults();
        $this->assertCount(2, $relations);
        $this->assertEquals($this->related1->id, $relations[0]->id);
        $this->assertEquals($this->related2->id, $relations[1]->id);
    }

    /** @test */
    public function it_returns_collection_instance_if_one_relation_exists()
    {
        $model = $this->getModel('many_relation');
        factory(RelationModel::class, 1)->create([
            'name' => 'many_relation',
            'from' => $this->model,
            'to'   => $this->related1,
        ]);

        $this->assertInstanceOf(Collection::class, $model->many_relation()->getResults());
    }

    /** @test */
    public function it_returns_collection_instance_if_multiple_relation_exists()
    {
        $model = $this->getModel('many_relation');
        $this->createFormRelation('many_relation', 3);

        $this->assertInstanceOf(Collection::class, $model->many_relation()->getResults());
    }

    protected function createFormRelation($name, $count = 1)
    {
        factory(RelationModel::class, $count)->create([
            'name' => $name,
            'from' => $this->model,
            'to'   => $this->related1,
        ]);
        factory(RelationModel::class, $count)->create([
            'name' => $name,
            'from' => $this->model,
            'to'   => $this->related2,
        ]);
    }

    protected function getModel($name)
    {
        $model = m::mock($this->model)->makePartial();
        $model->shouldReceive($name)->andReturn(
            $this->model->manyRelation(get_class($this->related1), $name)
        );

        return $model;
    }
}
