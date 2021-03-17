<?php

namespace Ignite\Crud\Config;

use ErrorException;
use Ignite\Config\ConfigHandler;
use Ignite\Crud\Config\Traits\HasCrudIndex;
use Ignite\Crud\Config\Traits\HasCrudShow;
use Ignite\Support\Facades\Config;
use Ignite\Support\Facades\Crud;
use Ignite\Vue\Component;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class CrudConfig
{
    use HasCrudShow,
        HasCrudIndex,
        Concerns\ManagesActions,
        Concerns\ManagesPermissions;

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
     * Wether the model should be sortable.
     *
     * @var bool
     */
    public $sortable = false;

    /**
     * Get the form name for the given Model.
     *
     * @param  Model  $model
     * @return string
     */
    public function getFormNameFor($model)
    {
        return 'show';
    }

    /**
     * Get config handler.
     *
     * @return ConfigHandler
     */
    public function get()
    {
        return Config::get(static::class);
    }

    /**
     * Get the route for the given model.
     *
     * @param  Model  $model
     * @return string
     */
    public function getRouteFor($model)
    {
        $uri = implode('/', [
            $this->get()->routePrefix(),
            $model->getKey(),
            $this->getRouteSuffix($this->getFormNameFor($model)),
        ]);

        return rtrim($uri, '\//');
    }

    /**
     * Get route suffix for the given form name.
     *
     * @param  string $formName
     * @return string
     */
    public function getRouteSuffix(string $formName)
    {
        if ($formName == 'show') {
            return '';
        }

        return $formName;
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

            try {
                $this->modelInstance = $this->controllerInstance()
                    ->getQuery()
                    ->find($id);
            } catch (ErrorException $e) {
                //
            }

            break;
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
    public function permissions(): array
    {
        $permissions = [];
        $operations = ['create', 'read', 'update', 'delete'];

        foreach ($operations as $operation) {
            $permissions[$operation] = lit_user()
                ? $this->authorize(lit_user(), $operation)
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

        return Config::get($this->parent[0]);
    }

    /**
     * Get the parent relation name.
     *
     * @return string
     */
    public function relation()
    {
        return $this->parent[1];
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
     * Get breadcrumb.
     *
     * @return string
     */
    public function breadcrumb()
    {
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

    public function createButton(): Component
    {
        return component('b-button')
            ->variant('primary')
            ->child($this->createButtonText())
            ->prop('href', lit()->url($this->get()->routePrefix().'/create'));
    }

    /**
     * Get create button text.
     *
     * @param  ConfigHandler $config
     * @return string
     */
    protected function createButtonText()
    {
        return ucfirst(
            __lit('base.item_create', ['item' => $this->names()['singular']])
        );
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
