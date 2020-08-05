<?php

namespace Tests\CrudRepositories;

use Fjord\Config\ConfigHandler;
use Fjord\Crud\Api\ApiRepositories;
use Fjord\Crud\BaseForm;
use Fjord\Crud\Controllers\CrudController;
use Fjord\Crud\Fields\Relations\MorphOne;
use Fjord\Crud\Repositories\Relations\MorphOneRepository;
use Fjord\Crud\Requests\CrudUpdateRequest;
use FjordTest\BackendTestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mockery as m;

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
    public function test_destroy_method()
    {
        $post = MorphOneRepositoryPost::create();
        $image = MorphOneRepositoryImage::create([
            'imageable_type' => MorphOneRepositoryPost::class,
            'imageable_id'   => $post->id,
        ]);

        $request = m::mock(CrudUpdateRequest::class);
        $request->related_id = $image->id;

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
