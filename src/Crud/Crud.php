<?php

namespace Ignite\Crud;

use Ignite\Crud\Requests\CrudCreateRequest;
use Ignite\Crud\Requests\CrudDeleteRequest;
use Ignite\Crud\Requests\CrudReadRequest;
use Ignite\Crud\Requests\CrudUpdateRequest;
use Ignite\Routing\Router as LitstackRouter;
use Ignite\Support\Facades\Lit;
use Ignite\Support\Facades\Nav;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Lit\Models\User;

/**
 * Crud singleton.
 *
 * @see \Ignite\Support\Facades\Crud
 */
class Crud
{
    /**
     * Litstack Router instance.
     *
     * @var LitstackRouter
     */
    protected $router;

    /**
     * Create new Crud instance.
     *
     * @param  LitstackRouter $router
     * @return void
     */
    public function __construct(LitstackRouter $router)
    {
        $this->router = $router;
    }

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
        $this->router->group(function () use ($config) {
            Route::group([
                'config' => $config->getKey(),
                'prefix' => "$config->routePrefix",
                'as'     => $config->getKey().'.',
            ], function () use ($config) {
                $this->makeCrudRoutes($config);
                $this->makeFieldRoutes($config->controller);

                Nav::preset(
                    [
                        get_class($config->getConfig()),
                        $config->getKey(),
                    ],
                    [
                        'link'      => Lit::url($config->routePrefix),
                        'title'     => fn () => ucfirst($config->names['plural']),
                        'authorize' => function (User $user) use ($config) {
                            return (new $config->controller())->authorize($user, 'read');
                        },
                    ]
                );
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
        $this->router->group(function () use ($config) {
            $form = $config->formName;
            $collection = $config->collection;

            Route::group([
                'config' => $config->getKey(),
                'prefix' => $config->route_prefix,
                'as'     => $config->getKey().'.',
            ], function () use ($config, $collection, $form) {
                //require lit_vendor_path('src/Crud/routes.php');
                $this->makeFormRoutes($config->controller);
                $this->makeFieldRoutes($config->controller);

                // Nav preset.
                Nav::preset(
                    [
                        get_class($config->getConfig()),
                        "form.{$collection}.{$form}",
                    ],
                    [
                        'link'      => Lit::url($config->route_prefix),
                        'title'     => fn () => ucfirst($config->names['singular']),
                        'authorize' => function (User $user) use ($config) {
                            return (new $config->controller())->authorize($user, 'read');
                        },
                    ]
                );
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
        Route::get('/', [$controller, 'show'])->name('show');
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

        Route::post('/order', [$controller, 'order'])->name('order');
        Route::post('/delete-all', [$controller, 'destroyAll'])->name('destroy.all');
        Route::delete('/{id}', [$controller, 'destroy'])->name('destroy');

        // Index routes.
        if ($config->has('index')) {
            Route::get('/', [$controller, 'index'])->name('index');
            Route::post('/index', [$controller, 'indexTable'])->name('index.items');
        }

        // Show routes.
        if ($config->has('show')) {
            Route::post('/{form}', [$controller, 'store'])->name('store');
            Route::get('/create', [$controller, 'create'])->name('create');
            Route::get("{{$identifier}}", [$controller, 'show'])->name('show');
        }
    }

    /**
     * Make field routes.
     *
     * @return void
     */
    protected function makeFieldRoutes(string $controller, string $identifier = 'id')
    {
        Route::post('/run-action/{key}', [$controller, 'runAction']);
        // Api
        Route::any('/api/{form_type}/{repository?}/{method?}/{child_method?}', [$controller, 'api'])->name('api');
        Route::any('/{id}/api/{form_type}/{repository?}/{method?}/{child_method?}', [$controller, 'api'])->name('api');

        // List
        Route::get("/{{$identifier}}/{form}/list/{field_id}", [$controller, 'loadListItems'])->name('list.index');
        Route::get("/{{$identifier}}/{form}/list/{field_id}/{list_item_id}", [$controller, 'loadListItem'])->name('list.load');
    }
}
