<?php

namespace Tests\CrudRepositories;

use Ignite\Config\ConfigHandler;
use Ignite\Crud\BaseForm;
use Ignite\Crud\Controllers\CrudController;
use Ignite\Crud\CrudJs;
use Ignite\Crud\Field;
use Ignite\Crud\Repositories\DefaultRepository;
use Ignite\Crud\Requests\CrudReadRequest;
use Illuminate\Database\Eloquent\Model;
use Mockery as m;
use Tests\BackendTestCase;

class DefaultRepositoryTest extends BackendTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->config = m::mock(ConfigHandler::class);
        $controller = m::mock(CrudController::class);
        $form = m::mock(BaseForm::class);
        $this->field = m::mock(Field::class)->makePartial();
        $this->repository = new DefaultRepository($this->config, $controller, $form, $this->field);
    }

    /** @test */
    public function test_load_method_returns_CrudJs()
    {
        $request = m::mock(CrudReadRequest::class);
        $model = m::mock(Model::class);

        $this->assertInstanceof(
            CrudJs::class, $this->repository->load($request, $model)
        );
    }
}
