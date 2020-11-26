<?php

namespace Tests\CrudRepositories;

use Mockery as m;
use Ignite\Crud\Field;
use Ignite\Crud\BaseForm;
use Tests\BackendTestCase;
use Ignite\Config\ConfigHandler;
use Illuminate\Support\Facades\DB;
use Ignite\Crud\Api\ApiRepositories;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Ignite\Crud\Controllers\CrudController;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Ignite\Crud\Fields\Relations\BelongsToMany;
use Ignite\Crud\Repositories\BaseFieldRepository;
use Ignite\Crud\Repositories\Relations\BelongsToManyRepository;

class BaseFieldRepositoryTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('roles');
        Schema::create('users', fn ($table) => $table->id());
        Schema::create('roles', function ($table) { 
            $table->id();
            $table->integer('order_column')->nullable();
        });
        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->integer('order_column')->nullable();
        });

        $config = m::mock(ConfigHandler::class);
        $controller = m::mock(CrudController::class);
        $form = m::mock(BaseForm::class);
        $this->field = m::mock(Field::class)->makePartial();
        $this->repository = new TestBaseFieldRepository($config, $controller, $form, $this->field);
    }

    public function tearDown(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_user');
        parent::tearDown();
    }

    /** @test */
    public function test_orderField_orders_models()
    {
        $role1 = BaseFieldRepositoryRole::create();
        $role2 = BaseFieldRepositoryRole::create();

        $this->field->orderColumn = 'order_column';

        $query = BaseFieldRepositoryRole::query();

        $ids = [$role2->id, $role1->id];

        $this->repository->orderField($query, $this->field, $ids);

        $this->assertEquals(0, $role2->refresh()->order_column);
        $this->assertEquals(1, $role1->refresh()->order_column);
    }

    /** @test */
    public function test_orderField_orders_belongsToMany_relations()
    {
        $user1 = BaseFieldRepositoryUser::create();
        $user2 = BaseFieldRepositoryUser::create();
        $role = BaseFieldRepositoryRole::create();
        $role->users()->attach($user1->id);
        $role->users()->attach($user2->id);

        $this->field->orderColumn = 'order_column';

        $query = BaseFieldRepositoryRole::query();

        $ids = [$user2->id, $user1->id];

        $this->repository->orderField($role->users(), $this->field, $ids);

        $users = $role->users()->withPivot('order_column')->get();
        

        $this->assertEquals(
            0, $users->where('id', $user2->id)->first()->pivot->order_column
        );
        $this->assertEquals(
            1, $users->where('id', $user1->id)->first()->pivot->order_column
        );
    }
}

class TestBaseFieldRepository extends BaseFieldRepository
{

}

class BaseFieldRepositoryUser extends Model
{
    public $table = 'users';
    public $timestamps = false;

    public function roles()
    {
        return $this->belongsToMany(
            BaseFieldRepositoryRole::class, 'role_user', 'user_id', 'role_id'
        );
    }
}
class BaseFieldRepositoryRole extends Model
{
    public $table = 'roles';
    public $timestamps = false;

    protected $fillable = ['order_column'];

    public function users()
    {
        return $this->belongsToMany(
            BaseFieldRepositoryUser::class, 'role_user', 'role_id', 'user_id'
        );
    }
}
