<?php

namespace Tests\CrudRepositories;

use Ignite\Config\ConfigHandler;
use Ignite\Crud\Api\ApiRepositories;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Controllers\CrudController;
use Ignite\Crud\Fields\Relations\MorphOne;
use Ignite\Crud\Repositories\Relations\MorphOneRepository;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mockery as m;
use Tests\BackendTestCase;

/**
 * @see https://laravel.com/docs/7.x/eloquent-relationships#one-to-one-polymorphic-relations
 */
class MorphOneRepositoryTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Schema::create('posts', fn ($table) => $table->id());
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('imageable_id')->nullable();
            $table->string('imageable_type')->nullable();
        });

        $config = m::mock(ConfigHandler::class);
        $controller = m::mock(CrudController::class);
        $form = m::mock(BaseForm::class);
        $this->field = m::mock(MorphOne::class)->makePartial();
        $this->repository = new MorphOneRepository($config, $controller, $form, $this->field);
    }

    public function tearDown(): void
    {
        Schema::dropIfExists('posts');
        Schema::dropIfExists('images');
        parent::tearDown();
    }

    /** @test */
    public function test_create_method()
    {
        $post = MorphOneRepositoryPost::create();
        $image = MorphOneRepositoryImage::create();

        $request = m::mock(CrudUpdateRequest::class);
        $request->related_id = $image->id;

        $this->field->shouldReceive('getQuery')->andReturn($image->query());
        $this->field->shouldReceive('getRelationQuery')->andReturn($post->image());
        $this->repository->create($request, $post);

        $this->assertNotNull($post->refresh()->image);
        $this->assertEquals($image->id, $post->refresh()->image->id);
    }

    /** @test */
    public function test_create_unsets_previous()
    {
        $post = MorphOneRepositoryPost::create();
        $image1 = MorphOneRepositoryImage::create([
            'imageable_type' => MorphOneRepositoryPost::class,
            'imageable_id'   => $post->id,
        ]);
        $image2 = MorphOneRepositoryImage::create();

        $request = m::mock(CrudUpdateRequest::class);
        $request->related_id = $image2->id;

        $this->field->shouldReceive('getQuery')->andReturn($image2->query());
        $this->field->shouldReceive('getRelationQuery')->andReturn($post->image());
        $this->repository->create($request, $post);

        $this->assertNotNull($post->refresh()->image);
        $this->assertEquals($image2->id, $post->refresh()->image->id);
        $this->assertEmpty($image1->refresh()->imageable_type);
        $this->assertEmpty($image1->refresh()->imageable_id);
    }

    /** @test */
    public function test_destroy_method()
    {
        $post = MorphOneRepositoryPost::create();
        $image = MorphOneRepositoryImage::create([
            'imageable_type' => MorphOneRepositoryPost::class,
            'imageable_id'   => $post->id,
        ]);

        $request = m::mock(CrudUpdateRequest::class);
        $request->related_id = $image->id;
        $request->delete_unlinked = false;

        $this->field->shouldReceive('getQuery')->andReturn($image->query());
        $this->field->shouldReceive('getRelationQuery')->andReturn($post->image());
        $this->repository->destroy($request, $post);

        $this->assertNull($post->refresh()->image);
    }

    /** @test */
    public function test_repository_is_registered()
    {
        $this->assertEquals(
            MorphOneRepository::class,
            app(ApiRepositories::class)->find('morph-one')
        );
    }
}

class MorphOneRepositoryPost extends Model
{
    public $table = 'posts';
    public $timestamps = false;

    public function image()
    {
        return $this->morphOne(MorphOneRepositoryImage::class, 'imageable');
    }
}

class MorphOneRepositoryImage extends Model
{
    public $table = 'images';
    public $timestamps = false;
    protected $fillable = ['imageable_id', 'imageable_type'];
}
