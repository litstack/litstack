<?php

namespace FjordTest\Crud;

use FjordTest\BackendTestCase;
use FjordTest\Traits\InteractsWithConfig;
use Illuminate\Database\Eloquent\Model;

class CrudTest extends BackendTestCase
{
    use InteractsWithConfig;

    public function getConfig(string $key, ...$params)
    {
        $this->calledConfigKey = $key;

        return new DummyCrudConfig();
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
