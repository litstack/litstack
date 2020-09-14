<?php

namespace Ignite\Crud\Config;

use Ignite\Crud\Config\Traits\HasCrudIndex;
use Ignite\Crud\Config\Traits\HasCrudShow;
use Ignite\Support\Facades\Config;
use Ignite\Support\Facades\Crud;
use Illuminate\Support\Str;

abstract class CrudConfig
{
    use HasCrudShow;
    use HasCrudIndex;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller;

    /**
     * Model class.
     *
     * @var string
     */
    public $model;

    /**
     * Set bootstrap container on index page to fluid.
     *
     * @var bool
     */
    public $expandIndexContainer = false;

    /**
     * Set bootstrap container on create and update page to fluid.
     *
     * @var bool
     */
    public $expandFormContainer = false;

    /**
     * Order column for model.
     *
     * @var string
     */
    public $orderColumn = 'order_column';

    /**
     * Crud permissions for operations create, read, update and delete.
     *
     * @return array
     */
    public function permissions()
    {
        $permissions = [];
        $operations = ['create', 'read', 'update', 'delete'];

        foreach ($operations as $operation) {
            $permissions[$operation] = lit_user()
                ? Crud::authorize($this->controller, $operation)
                : false;
        }

        return $permissions;
    }

    /**
     * Get the parent config.
     *
     * @return void
     */
    public function parentConfig()
    {
        if (! $this->parent) {
            return;
        }

        return Config::get($this->parent);
    }

    /**
     * Get a new controller instance.
     *
     * @return void
     */
    public function controllerInstance()
    {
        return app()->make($this->controller, [
            'config' => Config::get(get_class($this)),
        ]);
    }

    /**
     * Get crud route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return 'crud/'.Str::slug((new $this->model())->getTable());
    }

    /**
     * Model singular and plural name.
     *
     * @return array
     */
    public function names()
    {
        $tableName = (new $this->model())->getTable();

        return [
            'singular' => ucfirst(Str::singular($tableName)),
            'plural'   => ucfirst($tableName),
        ];
    }

    /**
     * Index component.
     *
     * @param  Component $component
     * @return void
     */
    public function indexComponent($component)
    {
        //
    }

    /**
     * Form component.
     *
     * @param  Component $component
     * @return void
     */
    public function formComponent($component)
    {
        //
    }
}
