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
