<?php

namespace Ignite\Crud\Config;

use Ignite\Crud\Config\Traits\HasCrudIndex;
use Ignite\Crud\Config\Traits\HasCrudShow;
use Ignite\Support\Facades\Config;
use Ignite\Support\Facades\Crud;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
     * The model instance.
     *
     * @var Model
     */
    protected $modelInstance;

    /**
     * Create new CrudConfig instance.
     *
     * @return void
     */
    public function __construct()
    {
        app()->booted(function () {
            $this->setModelInstanceFromCurrentRoute();
        });
    }

    /**
     * Set model instance from current route.
     *
     * @return void
     */
    public function setModelInstanceFromCurrentRoute()
    {
        try {
            $route = Route::getRoutes()->match(request());
        } catch (HttpException $e) {
            return;
        }

        if (! $route) {
            return;
        }

        $search = str_replace('.', '_', Config::getKey(static::class));

        foreach ($route->parameters as $parameter => $id) {
            if ($parameter != $search) {
                continue;
            }

            $this->modelInstance = $this->controllerInstance()
                ->getQuery()
                ->find($id);
        }
    }

    /**
     * Set model instance.
     *
     * @param  Model $model
     * @return void
     */
    public function setModelInstance(Model $model)
    {
        $this->modelInstance = $model;
    }

    /**
     * Get model instance.
     *
     * @return Model|null
     */
    public function getModelInstance()
    {
        return $this->modelInstance;
    }

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
