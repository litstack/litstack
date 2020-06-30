<?php

namespace Fjord\Crud\Config;

use Fjord\Crud\Config\Traits\HasCrudIndex;
use Fjord\Crud\Config\Traits\HasCrudShow;
use Fjord\Support\Facades\Crud;
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
            $permissions[$operation] = fjord_user()
                ? Crud::authorize($this->controller, $operation)
                : false;
        }

        return $permissions;
    }

    /**
     * Get crud route prefix.
     *
     * @return string $route
     */
    public function routePrefix()
    {
        return 'crud/'.(new $this->model())->getTable();
    }

    /**
     * Model singular and plural name.
     *
     * @return array $names
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
     * @param Component $component
     *
     * @return void
     */
    public function indexComponent($component)
    {
        //
    }

    /**
     * Form component.
     *
     * @param Component $component
     *
     * @return void
     */
    public function formComponent($component)
    {
        //
    }
}
