<?php

namespace FjordTest\Crud;

use FjordTest\BackendTestCase;
use Fjord\Support\Facades\Crud;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use FjordTest\Traits\InteractsWithConfig;

class CrudTest extends BackendTestCase
{
    use InteractsWithConfig;

    public function getConfig(string $key, ...$params)
    {
        $this->calledConfigKey = $key;
        return new DummyCrudConfig;
    }

    /** @test */
    public function test_config_loads_crud_config()
    {
        Crud::config('dummy_model');
        $this->assertEquals('crud.dummy_model', $this->calledConfigKey);
    }

    /** @test */
    public function test_routes_method()
    {
        Crud::routes('dummy_prefix', DummyCrudModel::class, DummyCrudController::class);
    }

    /** @test */
    public function test_formRoutes_method()
    {
        Crud::formRoutes('dummy_prefix', 'pages', 'home');
    }
}

class DummyCrudController
{
}

class DummyCrudModel extends Model
{
}

class DummyCrudConfig
{
    public $controller = 'DummyCrudController';
    public $names = ['singular' => '', 'plural' => ''];
}
