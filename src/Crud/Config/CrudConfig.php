<?php

namespace Lit\Crud\Config;

use Lit\Crud\Config\Traits\HasCrudIndex;
use Lit\Crud\Config\Traits\HasCrudShow;
use Lit\Support\Facades\Crud;
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
