<?php

namespace Fjord\Crud\Config;

use Illuminate\Support\Str;
use Fjord\Crud\Config\Traits\HasCrudForm;
use Illuminate\Database\Eloquent\Builder;
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
        $user = fjord_user();
        $controller = new $this->controller;
        return [
            'create' => $controller->authorize($user, 'create'),
            'read' => $controller->authorize($user, 'read'),
            'update' => $controller->authorize($user, 'update'),
            'delete' => $controller->authorize($user, 'delete'),
        ];
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
