<?php

namespace FjordTest\Crud;

use Fjord\Crud\Models\FormRelation;
use FjordTest\BackendTestCase;
use FjordTest\TestSupport\Models\Post;
use Illuminate\Database\Eloquent\Relations\Relation;
use Mockery as m;

class OneRelationMacroTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->installFjord();
        $this->migrate();

        // Setting up form relation.
        $this->model = Post::create([]);
        $this->related = Post::create([]);
    }

    /** @test */
    public function it_returns_null_when_not_relation_doesnt_exist()
    {
        $model = $this->getModel('one_relation');

        $this->assertNull($model->one_relation);
    }

    /** @test */
    public function it_returns_relation_instance_builder_when_called_as_method()
    {
        $model = $this->getModel('one_relation');

        $this->assertInstanceOf(Relation::class, $model->one_relation());
    }

    /** @test */
    public function it_finds_existing_related_model()
    {
        $model = $this->getModel('one_relation');
        $this->createFormRelation('one_relation', 1);

        $this->assertTrue($model->one_relation()->exists());
    }

    /** @test */
    public function it_finds_correct_related_model()
    {
        $model = $this->getModel('one_relation');
        $this->createFormRelation('one_relation', 1);

        $this->assertEquals($this->related->id, $model->one_relation()->getResults()->id);
    }

    /** @test */
    public function it_returns_one_even_if_there_are_many()
    {
        $model = $this->getModel('one_relation');
        $this->createFormRelation('one_relation', 2);

        $this->assertInstanceOf(get_class($this->related), $model->one_relation()->getResults());
    }

    protected function createFormRelation($name, $count = 1)
    {
        factory(FormRelation::class, $count)->create([
            'name' => $name,
            'from' => $this->model,
            'to'   => $this->related,
        ]);
    }

    protected function getModel($name)
    {
        $model = m::mock($this->model);
        $this->passthruAllExcept($model, $this->model, [$name]);
        $model->shouldReceive($name)->andReturn(
            $this->model->oneRelation(get_class($this->related), $name)
        );

        return $model;
    }
}
