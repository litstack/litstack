<?php

namespace Tests\CrudRepositories;

use Ignite\Config\ConfigHandler;
use Ignite\Crud\Api\ApiRepositories;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Controllers\CrudController;
use Ignite\Crud\Fields\Relations\ManyRelation;
use Ignite\Crud\Models\Relation;
use Ignite\Crud\Models\Relations\CrudRelations;
use Ignite\Crud\Repositories\Relations\HasManyRepository;
use Ignite\Crud\Repositories\Relations\ManyRelationRepository;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Mockery as m;
use Tests\BackendTestCase;

/**
 * @see https://laravel.com/docs/7.x/eloquent-relationships#one-to-man
 */
class ManyRepositoryTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Schema::create('posts', fn ($table) => $table->id());
        Schema::create('comments', fn ($table) => $table->id());

        $app = m::mock('app');
        // (new CrudRelations($app))->register();

        $config = m::mock(ConfigHandler::class);
        $controller = m::mock(CrudController::class);
        $form = m::mock(BaseForm::class);
        $this->field = m::mock(ManyRelation::class)->makePartial();
        $this->field->id = 'comments';
        $this->repository = new ManyRelationRepository($config, $controller, $form, $this->field);
    }

    public function tearDown(): void
    {
        Schema::dropIfExists('posts');
        Schema::dropIfExists('comments');
        parent::tearDown();
    }

    /** @test */
    public function test_create_method()
    {
        $post = ManyRepositoryPost::create();
        $comment1 = ManyRepositoryComment::create();
        $comment2 = ManyRepositoryComment::create();

        $this->field->shouldReceive('getRelationQuery')->andReturn($post->comments());

        $request = m::mock(CrudUpdateRequest::class);
        $request->related_id = $comment1->id;
        $this->field->shouldReceive('getQuery')->andReturn($comment1->query())->once();
        $this->repository->create($request, $post);

        $this->assertEquals(1, $post->refresh()->comments->count());
        $this->assertSame($comment1->id, $post->refresh()->comments->first()->id);

        $request = m::mock(CrudUpdateRequest::class);
        $request->related_id = $comment2->id;
        $this->field->shouldReceive('getQuery')->andReturn($comment2->query())->once();
        $this->repository->create($request, $post);

        $this->assertEquals(2, $post->refresh()->comments->count());
        $this->assertSame($comment2->id, $post->refresh()->comments->last()->id);
    }

    /** @test */
    public function test_destroy_method()
    {
        $post = ManyRepositoryPost::create();
        $comment = ManyRepositoryComment::create();

        factory(Relation::class, 1)->create([
            'name' => 'many_relation',
            'from' => $post,
            'to'   => $comment,
        ]);

        $request = m::mock(CrudUpdateRequest::class);
        $request->related_id = $comment->id;
        $request->delete_unlinked = false;

        $this->field->shouldReceive('getQuery')->andReturn($comment->query());
        $this->field->shouldReceive('getRelationQuery')->andReturn($post->comments());
        $this->repository->destroy($request, $post);

        $this->assertSame(0, $post->refresh()->comments->count());
    }

    /** @test */
    public function test_repository_is_registered()
    {
        $this->assertEquals(
            HasManyRepository::class,
            app(ApiRepositories::class)->find('has-many')
        );
    }
}

class ManyRepositoryPost extends Model
{
    public $table = 'posts';
    public $timestamps = false;

    public function comments()
    {
        return $this->manyRelation(ManyRepositoryComment::class, 'comments');
    }
}
class ManyRepositoryComment extends Model
{
    public $table = 'comments';
    public $timestamps = false;
}
