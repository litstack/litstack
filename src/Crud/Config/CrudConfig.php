<?php

namespace Fjord\Crud\Config;

use Illuminate\Support\Str;
use Fjord\Crud\Config\Traits\HasCrudForm;
use Fjord\Crud\Config\Traits\HasCrudIndex;

abstract class CrudConfig
{
    use HasCrudForm,
        HasCrudIndex;

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
     * @var boolean
     */
    public $expandIndexContainer = false;

    /**
     * Set bootstrap container on create and update page to fluid.
     *
     * @var boolean
     */
    public $expandFormContainer = false;

    /**
     * Load permissions.
     *
     * @return array
     */
    public function permissions()
    {
        $permissions = [];
        $operations = ['create', 'read', 'update', 'delete'];
        $controller = new $this->controller;
        $user = fjord_user();

        foreach ($operations as $operation) {
            $permissions[$operation] = $user
                ? $controller->authorize($user, $operation)
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
        return 'crud/' . (new $this->model)->getTable();
    }

    /**
     * Model singular and plural name.
     *
     * @return array $names
     */
    public function names()
    {
        $tableName = (new $this->model)->getTable();

        return [
            'singular' => ucfirst(Str::singular($tableName)),
            'plural' => ucfirst($tableName),
        ];
    }

    /**
     * Index component.
     *
     * @param Component $component
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
     * @return void
     */
    public function formComponent($component)
    {
        //
    }
}
