<?php

namespace Fjord\Crud;

use Fjord\Crud\Requests\CrudCreateRequest;
use Fjord\Crud\Requests\CrudDeleteRequest;
use Fjord\Crud\Requests\CrudReadRequest;
use Fjord\Crud\Requests\CrudUpdateRequest;
use Fjord\Support\Facades\Fjord;
use Fjord\Support\Facades\Package;
use Fjord\User\Models\FjordUser;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Crud singleton.
 *
 * @see \Fjord\Support\Facades\Crud
 */
class Crud
{
    /**
     * Get model names.
     *
     * @return array
     */
    public function names(string $model)
    {
        $modelInstance = new $model();
        if (method_exists($modelInstance, 'names')) {
            return $modelInstance->names();
        }

        return [
            'singular' => class_basename($model),
            'plural'   => Str::plural(class_basename($model)),
        ];
    }

    /**
     * Authorize crud controller.
     *
     * @param  string $controller
     * @param  string $operation
     * @return bool
     */
    public function authorize(string $controller, string $operation)
    {
        switch ($operation) {
            case 'create':
                return (new CrudCreateRequest())->authorizeController(app('request'), 'create', $controller);
                break;
            case 'read':
                return (new CrudReadRequest())->authorize(app('request'), 'read', $controller);
                break;
            case 'update':
                return (new CrudUpdateRequest())->authorize(app('request'), 'update', $controller);
                break;
            case 'delete':
                return (new CrudDeleteRequest())->authorize(app('request'), 'delete', $controller);
                break;
        }

        throw new InvalidArgumentException('Operation must be create, read, update or delete');
    }

    /**
     * Make routes for Crud Model.
     *
     * @param  string $prefix
     * @param  string $model
     * @param  string $controller
     * @return void
     */
    public function routes($config)
    {
        Package::get('aw-studio/fjord')->route()->group(function () use ($config) {
            RouteFacade::group([
                'config' => $config->getKey(),
                'prefix' => "$config->routePrefix",
                'as'     => $config->getKey().'.',
            ], function () use ($config) {
                $this->makeCrudRoutes($config);
                $this->makeFieldRoutes($config->controller);

                Package::get('aw-studio/fjord')->addNavPreset($config->getKey(), [
                    'link'      => Fjord::url($config->routePrefix),
                    'title'     => fn ()     => ucfirst($config->names['plural']),
                    'authorize' => function (FjordUser $user) use ($config) {
                        return (new $config->controller())->authorize($user, 'read');
                    },
                ]);
            });
        });
    }

    /**
     * Make routes for Forms.
     *
     * @param  ConfigHandler $config
     * @return void
     */
    public function formRoutes($config)
    {
        Package::get('aw-studio/fjord')->route()->group(function () use ($config) {
            $form = $config->formName;
            $collection = $config->collection;

            RouteFacade::group([
                'config' => $config->getKey(),
                'prefix' => $config->route_prefix,
                'as'     => $config->getKey().'.',
            ], function () use ($config, $collection, $form) {
                //require fjord_path('src/Crud/routes.php');
                $this->makeFormRoutes($config->controller);
                $this->makeFieldRoutes($config->controller);

                // Nav preset.
                Package::get('aw-studio/fjord')->addNavPreset("form.{$collection}.{$form}", [
                    'link'      => Fjord::url($config->route_prefix),
                    'title'     => fn ()     => ucfirst($config->names['singular']),
                    'authorize' => function (FjordUser $user) use ($config) {
                        return (new $config->controller())->authorize($user, 'read');
                    },
                ]);
            });
        });
    }

    /**
     * Make form routes.
     *
     * @return void
     */
    protected function makeFormRoutes(string $controller)
    {
        RouteFacade::get('/', [$controller, 'show'])->name('show');
    }

    /**
     * Make Crud Model routes.
     *
     * @param  string $controller
     * @return void
     */
    protected function makeCrudRoutes($config, string $identifier = 'id')
    {
        $controller = $config->controller;

        RouteFacade::post('/order', [$controller, 'order'])->name('order');
        RouteFacade::post('/delete-all', [$controller, 'destroyAll'])->name('destroy.all');
        RouteFacade::delete('/{id}', [$controller, 'destroy'])->name('destroy');

        // Index routes.
        if ($config->has('index')) {
            RouteFacade::get('/', [$controller, 'index'])->name('index');
            RouteFacade::post('/index', [$controller, 'indexTable'])->name('index.items');
        }

        // Show routes.
        if ($config->has('show')) {
            RouteFacade::post('/{form}', [$controller, 'store'])->name('store');
            RouteFacade::get('/create', [$controller, 'create'])->name('create');
            RouteFacade::get("{{$identifier}}", [$controller, 'show'])->name('show');
        }
    }

    /**
     * Make field routes.
     *
     * @return void
     */
    protected function makeFieldRoutes(string $controller, string $identifier = 'id')
    {
        RouteFacade::post('/run-action/{key}', [$controller, 'runAction']);
        // Api
        RouteFacade::any('/api/{form_type}/{repository?}/{method?}/{child_method?}', [$controller, 'api'])->name('api');
        RouteFacade::any('/{id}/api/{form_type}/{repository?}/{method?}/{child_method?}', [$controller, 'api'])->name('api');

        // List
        RouteFacade::get("/{{$identifier}}/{form}/list/{field_id}", [$controller, 'loadListItems'])->name('list.index');
        RouteFacade::get("/{{$identifier}}/{form}/list/{field_id}/{list_item_id}", [$controller, 'loadListItem'])->name('list.load');
    }
}
