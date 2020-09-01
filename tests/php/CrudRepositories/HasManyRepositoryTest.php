<?php

namespace Tests\CrudRepositories;

use Ignite\Config\ConfigHandler;
use Ignite\Crud\Api\ApiRepositories;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Controllers\CrudController;
use Ignite\Crud\Fields\Relations\HasMany;
use Ignite\Crud\Repositories\Relations\HasManyRepository;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mockery as m;
use Tests\BackendTestCase;

/**
 * @see https://laravel.com/docs/7.x/eloquent-relationships#one-to-man
 */
class HasManyRepositoryTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Schema::create('posts', fn ($table) => $table->id());
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id')->default(null)->nullable();
        });

        $config = m::mock(ConfigHandler::class);
        $controller = m::mock(CrudController::class);
        $form = m::mock(BaseForm::class);
        $this->field = m::mock(HasMany::class)->makePartial();
        $this->repository = new HasManyRepository($config, $controller, $form, $this->field);
    }

    public function tearDown(): void
    {
        Schema::dropIfExists('posts');
        Schema::dropIfExists('comments');
    }

    /** @test */
    public function test_create_method()
    {
        $post = HasManyRepositoryPost::create();
        $comment1 = HasManyRepositoryComment::create();
        $comment2 = HasManyRepositoryComment::create();

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
        $post = HasManyRepositoryPost::create();
        $comment = HasManyRepositoryComment::create([
            'post_id' => $post->id,
        ]);

        $request = m::mock(CrudUpdateRequest::class);
        $request->related_id = $comment->id;

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

class HasManyRepositoryPost extends Model
{
    public $table = 'posts';
    public $timestamps = false;

    public function comments()
    {
        return $this->hasMany(HasManyRepositoryComment::class, 'post_id');
    }
}
class HasManyRepositoryComment extends Model
{
    public $table = 'comments';
    public $timestamps = false;
    protected $fillable = ['post_id'];
}
