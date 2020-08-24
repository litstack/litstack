<?php

namespace Tests\CrudRepositories;

use Lit\Config\ConfigHandler;
use Lit\Crud\Api\ApiRepositories;
use Lit\Crud\BaseForm;
use Lit\Crud\Controllers\CrudController;
use Lit\Crud\Fields\Relations\BelongsToMany;
use Lit\Crud\Repositories\Relations\BelongsToManyRepository;
use Lit\Crud\Requests\CrudUpdateRequest;
use Tests\BackendTestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery as m;

/**
 * @see https://laravel.com/docs/7.x/eloquent-relationships#many-to-many
 */
class BelongsToManyRepositoryTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('roles');
        Schema::create('users', fn ($table) => $table->id());
        Schema::create('roles', fn ($table) => $table->id());
        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->integer('order_column')->nullable();
        });

        $config = m::mock(ConfigHandler::class);
        $controller = m::mock(CrudController::class);
        $form = m::mock(BaseForm::class);
        $this->field = m::mock(BelongsToMany::class)->makePartial();
        $this->repository = new BelongsToManyRepository($config, $controller, $form, $this->field);
    }

    public function tearDown(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_user');
    }

    /** @test */
    public function test_create_method()
    {
        $user = BelongsToManyRepositoryUser::create();
        $role = BelongsToManyRepositoryRole::create();

        $request = m::mock(CrudUpdateRequest::class);
        $request->related_id = $user->id;

        $this->field->sortable = false;
        $this->field->shouldReceive('getQuery')->andReturn($user->query());
        $this->field->shouldReceive('getRelationQuery')->andReturn($role->users());
        $this->repository->create($request, $role);

        $this->assertCount(1, $role->refresh()->users);
        $this->assertEquals($user->id, $role->users->first()->id);
    }

    /** @test */
    public function test_create_method_adds_correct_order_column_when_field_is_sortable()
    {
        $user1 = BelongsToManyRepositoryUser::create();
        $user2 = BelongsToManyRepositoryUser::create();
        $role = BelongsToManyRepositoryRole::create();

        //dd(BelongsToManyRepositoryUser::query()->findOrFail($user2->id));

        $this->field->sortable = true;
        $this->field->orderColumn = 'order_column';
        $this->field->shouldReceive('getQuery')->once()->andReturn(BelongsToManyRepositoryUser::query());
        $this->field->shouldReceive('getQuery')->once()->andReturn(BelongsToManyRepositoryUser::query());
        $this->field->shouldReceive('getRelationQuery')->twice()->andReturn($role->users());

        $request = m::mock(CrudUpdateRequest::class);
        $request->related_id = $user1->id;
        $this->repository->create($request, $role);
        $request->related_id = $user2->id;
        $this->repository->create($request, $role);

        $this->assertCount(2, $role->refresh()->users);
        $ordered = $role->users()->orderBy('order_column')->get();
        $this->assertEquals($user1->id, $ordered[0]->id);
        $this->assertEquals($user2->id, $ordered[1]->id);
    }

    /** @test */
    public function test_destroy_method()
    {
        $user = BelongsToManyRepositoryUser::create();
        $role = BelongsToManyRepositoryRole::create();
        DB::table('role_user')->insert([
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);

        $this->assertCount(1, $role->refresh()->users);

        $request = m::mock(CrudUpdateRequest::class);
        $request->related_id = $user->id;

        $this->field->shouldReceive('getQuery')->andReturn($user->query());
        $this->field->shouldReceive('getRelationQuery')->andReturn($role->users());
        $this->repository->destroy($request, $role);

        $this->assertCount(0, $role->refresh()->users);
    }

    /** @test */
    public function test_repository_is_registered()
    {
        $this->assertEquals(
            BelongsToManyRepository::class,
            app(ApiRepositories::class)->find('belongs-to-many')
        );
    }
}

class BelongsToManyRepositoryUser extends Model
{
    public $table = 'users';
    public $timestamps = false;

    public function roles()
    {
        return $this->belongsToMany(
            BelongsToManyRepositoryRole::class, 'role_user', 'user_id', 'role_id'
        );
    }
}
class BelongsToManyRepositoryRole extends Model
{
    public $table = 'roles';
    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany(
            BelongsToManyRepositoryUser::class, 'role_user', 'role_id', 'user_id'
        );
    }
}
