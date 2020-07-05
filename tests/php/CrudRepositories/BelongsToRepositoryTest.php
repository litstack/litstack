<?php

namespace FjordTest\CrudRepositories;

use Fjord\Config\ConfigHandler;
use Fjord\Crud\Api\ApiRepositories;
use Fjord\Crud\BaseForm;
use Fjord\Crud\Controllers\CrudController;
use Fjord\Crud\Fields\Relations\BelongsTo;
use Fjord\Crud\Repositories\Relations\BelongsToRepository;
use Fjord\Crud\Requests\CrudUpdateRequest;
use FjordTest\BackendTestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mockery as m;

/**
 * @see https://laravel.com/docs/7.x/eloquent-relationships#one-to-one
 */
class BelongsToRepositoryTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Schema::create('users', fn ($table) => $table->id());
        Schema::create('phone', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(null)->nullable();
        });

        $config = m::mock(ConfigHandler::class);
        $controller = m::mock(CrudController::class);
        $form = m::mock(BaseForm::class);
        $this->field = m::mock(BelongsTo::class);
        $this->repository = new BelongsToRepository($config, $controller, $form, $this->field);
    }

    public function tearDown(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('phone');
    }

    /** @test */
    public function test_create_method()
    {
        $user = BelongsToRepositoryUser::create();
        $phone = BelongsToRepositoryPhone::create();

        $request = m::mock(CrudUpdateRequest::class);
        $request->related_id = $user->id;

        $this->field->shouldReceive('getQuery')->andReturn($user->query());
        $this->field->shouldReceive('getRelationQuery')->andReturn($phone->user());
        $this->repository->create($request, $phone);

        $this->assertEquals($user->id, $phone->refresh()->user->id);
    }

    /** @test */
    public function test_destroy_method()
    {
        $user = BelongsToRepositoryUser::create();
        $phone = BelongsToRepositoryPhone::create([
            'user_id' => $user->id,
        ]);

        $request = m::mock(CrudUpdateRequest::class);
        $request->related_id = $user->id;

        $this->field->shouldReceive('getRelationQuery')->andReturn($phone->user());
        $this->repository->destroy($request, $phone);

        $phone = BelongsToRepositoryPhone::first();

        $this->assertNull($phone->refresh()->user);
    }

    /** @test */
    public function test_repository_is_registered()
    {
        $this->assertEquals(
            BelongsToRepository::class,
            app(ApiRepositories::class)->find('belongs-to')
        );
    }
}

class BelongsToRepositoryUser extends Model
{
    public $table = 'users';
    public $timestamps = false;
}
class BelongsToRepositoryPhone extends Model
{
    public $table = 'phone';
    public $timestamps = false;
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(BelongsToRepositoryUser::class);
    }
}
