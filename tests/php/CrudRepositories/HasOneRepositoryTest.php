<?php

namespace Tests\CrudRepositories;

use Ignite\Config\ConfigHandler;
use Ignite\Crud\Api\ApiRepositories;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Controllers\CrudController;
use Ignite\Crud\Fields\Relations\HasOne;
use Ignite\Crud\Repositories\Relations\HasOneRepository;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Tests\BackendTestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mockery as m;

/**
 * @see https://laravel.com/docs/7.x/eloquent-relationships#one-to-one
 */
class HasOneRepositoryTest extends BackendTestCase
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
        $this->field = m::mock(HasOne::class);
        $this->repository = new HasOneRepository($config, $controller, $form, $this->field);
    }

    public function tearDown(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('phone');
    }

    /** @test */
    public function test_create_method()
    {
        $user = HasOneRepositoryUser::create();
        $phone = HasOneRepositoryPhone::create();

        $request = m::mock(CrudUpdateRequest::class);
        $request->related_id = $phone->id;

        $this->field->shouldReceive('getQuery')->andReturn($phone->query());
        $this->field->shouldReceive('getRelationQuery')->andReturn($user->phone());
        $this->repository->create($request, $user);

        $this->assertEquals($phone->id, $user->refresh()->phone->id);
    }

    /** @test */
    public function test_destroy_method()
    {
        $user = HasOneRepositoryUser::create();
        $phone = HasOneRepositoryPhone::create([
            'user_id' => $user->id,
        ]);

        $request = m::mock(CrudUpdateRequest::class);
        $request->related_id = $phone->id;

        $this->field->shouldReceive('getQuery')->andReturn($phone->query());
        $this->field->shouldReceive('getRelationQuery')->andReturn($user->phone());
        $this->repository->destroy($request, $user);

        $this->assertNull($user->refresh()->phone);
    }

    /** @test */
    public function test_repository_is_registered()
    {
        $this->assertEquals(
            HasOneRepository::class,
            app(ApiRepositories::class)->find('has-one')
        );
    }
}

class HasOneRepositoryUser extends Model
{
    public $table = 'users';
    public $timestamps = false;

    public function phone()
    {
        return $this->hasOne(HasOneRepositoryPhone::class, 'user_id');
    }
}
class HasOneRepositoryPhone extends Model
{
    public $table = 'phone';
    public $timestamps = false;
    protected $fillable = ['user_id'];
}
