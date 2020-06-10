<?php

namespace Fjord\Crud;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Fjord\Support\Facades\Fjord;
use Fjord\User\Models\FjordUser;
use Fjord\Crud\Config\CrudConfig;
use Fjord\Support\Facades\Config;
use Fjord\Support\Facades\Package;
use Fjord\Crud\Requests\CrudReadRequest;
use Fjord\Crud\Requests\CrudCreateRequest;
use Fjord\Crud\Requests\CrudDeleteRequest;
use Fjord\Crud\Requests\CrudUpdateRequest;
use Illuminate\Support\Facades\Route as RouteFacade;

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
     * @param string $model
     * @return array
     */
    public function names(string $model)
    {
        $modelInstance = new $model;
        if (method_exists($modelInstance, 'names')) {
            return $modelInstance->names();
        }

        return [
            'singular' => class_basename($model),
            'plural' => Str::plural(class_basename($model))
        ];
    }

    /**
     * Authorize crud controller.
     *
     * @param string $controller
     * @param string $key
     * @return boolean
     */
    public function authorize(string $controller, string $operation)
    {
        switch ($operation) {
            case 'create':
                return (new CrudCreateRequest)->authorizeController(app('request'), 'create', $controller);
                break;
            case 'read':
                return (new CrudReadRequest)->authorize(app('request'), 'read', $controller);
                break;
            case 'update':
                return (new CrudUpdateRequest)->authorize(app('request'), 'update', $controller);
                break;
            case 'delete':
                return (new CrudDeleteRequest)->authorize(app('request'), 'delete', $controller);
                break;
        }
        throw new InvalidArgumentException("Operation must be create, read, update or delete");
    }

    /**
     * Make routes for Crud Model.
     *
     * @param string $prefix
     * @param string $model
     * @param string $controller
     * @return void
     */
    public function routes($config)
    {
        Package::get('aw-studio/fjord')->route()->group(function () use ($config) {
            RouteFacade::group([
                'config' => $config->getKey(),
                'prefix' => "$config->routePrefix",
                'as' => $config->getKey(),
            ], function () use ($config) {

                $this->makeCrudRoutes($config);
                $this->makeFieldRoutes($config->controller);

                Package::get('aw-studio/fjord')->addNavPreset($config->getKey(), [
                    'link' => Fjord::url($config->routePrefix),
                    'title' => ucfirst($config->names['plural']),
                    'authorize' => function (FjordUser $user) use ($config) {
                        return (new $config->controller)->authorize($user, 'read');
                    }
                ]);
            });
        });
    }

    /**
     * Make routes for Forms.
     *
     * @param ConfigHandler $config
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
                'as' => $config->getKey(),
            ], function () use ($config, $collection, $form) {

                //require fjord_path('src/Crud/routes.php');
                $this->makeFormRoutes($config->controller);
                $this->makeFieldRoutes($config->controller);

                // Nav preset.
                Package::get('aw-studio/fjord')->addNavPreset("form.{$collection}.{$form}", [
                    'link' => Fjord::url($config->route_prefix),
                    'title' => ucfirst($config->names['singular']),
                    'authorize' => function (FjordUser $user) use ($config) {
                        return (new $config->controller)->authorize($user, 'read');
                    }
                ]);
            });
        });
    }

    /**
     * Make form routes.
     *
     * @param string $controller
     * @return void
     */
    protected function makeFormRoutes(string $controller)
    {
        RouteFacade::get("/", [$controller, "show"])->name('show');
    }

    /**
     * Make Crud Model routes.
     *
     * @param string $controller
     * @param string $identifier
     * @return void
     */
    protected function makeCrudRoutes($config, string $identifier = 'id')
    {
        $controller = $config->controller;

        RouteFacade::post("/order", [$controller, "order"])->name('order');
        RouteFacade::post("/delete-all", [$controller, "destroyAll"])->name('destroy.all');
        RouteFacade::delete("/{id}", [$controller, "destroy"])->name('destroy');

        // Index routes.
        if ($config->has('index')) {
            RouteFacade::get("/", [$controller, "index"])->name('index');
            RouteFacade::post("/index", [$controller, "indexTable"])->name('index.items');
        }

        // Show routes.
        if ($config->has('show')) {
            RouteFacade::post("/{form}", [$controller, "store"])->name('store');
            RouteFacade::get("/create", [$controller, "create"])->name('create');
            RouteFacade::get("{{$identifier}}", [$controller, "show"])->name('show');
        }
    }

    /**
     * Make field routes.
     *
     * @param string $controller
     * @return void
     */
    protected function makeFieldRoutes(string $controller, string $identifier = 'id')
    {
        // For refreshing.
        RouteFacade::get("/{{$identifier}}/load", [$controller, "load"])->name('load');

        // Update
        RouteFacade::put("/{{$identifier}}/{form}", [$controller, "update"])->name('update');

        // Modal
        RouteFacade::put("/{{$identifier}}/{form}/modal/{modal_id}", [$controller, 'updateModal'])->name("modal.update");

        // Media
        RouteFacade::put("/{{$identifier}}/{form}/media/order", [$controller, 'orderMedia'])->name("media.order");
        RouteFacade::put("/{{$identifier}}/{form}/media/{media_id}", [$controller, 'updateMedia'])->name("media.update");
        RouteFacade::post("/{{$identifier}}/{form}/media", [$controller, 'storeMedia'])->name("media.store");
        RouteFacade::delete("/{{$identifier}}/{form}/media/{media_id}", [$controller, 'destroyMedia'])->name("media.destroy");

        // Block
        RouteFacade::get("/{{$identifier}}/{form}/block/{field_id}", [$controller, "loadRepeatables"])->name("block.index");
        RouteFacade::get("/{{$identifier}}/{form}/block/{field_id}/{block_id}", [$controller, "loadRepeatable"])->name("block.index");
        RouteFacade::post("/{{$identifier}}/{form}/block/{field_id}", [$controller, "storeBlock"])->name("block.store");
        RouteFacade::put("/{{$identifier}}/{form}/block/{field_id}/{block_id}", [$controller, "updateRepeatable"])->name("block.update");
        RouteFacade::delete("/{{$identifier}}/{form}/block/{field_id}/{block_id}", [$controller, "destroyRepeatable"])->name("block.destroy");

        // Block Media
        RouteFacade::post("/{{$identifier}}/{form}/block/{field_id}/{block_id}/media", [$controller, "storeBlockMedia"])->name("block.media.store");
        RouteFacade::put("/{{$identifier}}/{form}/block/{field_id}/{block_id}/media/order", [$controller, 'orderBlockMedia'])->name("block.media.order");
        RouteFacade::put("/{{$identifier}}/{form}/block/{field_id}/{block_id}/media/{media_id}", [$controller, "updateBlockMedia"])->name("block.media.update");
        RouteFacade::delete("/{{$identifier}}/{form}/block/{field_id}/{block_id}/media/{media_id}", [$controller, "destroyBlockMedia"])->name("block.media.destroy");

        // Block Relations
        RouteFacade::post("/{{$identifier}}/{form}/block/{field_id}/{block_id}/{relation}/index", [$controller, "blockRelationIndex"])->name("block.relation.index");
        RouteFacade::post("/{{$identifier}}/{form}/block/{field_id}/{block_id}/{relation}", [$controller, "loadBlockRelations"])->name("block.relation.load");
        RouteFacade::put("/{{$identifier}}/{form}/block/{field_id}/{block_id}/{relation}/order", [$controller, "orderBlockRelation"])->name("block.relation.order");
        RouteFacade::delete("/{{$identifier}}/{form}/block/{field_id}/{block_id}/{relation}/{relation_id}",  [$controller, "destroyBlockRelation"])->name("block.relation.delete");
        RouteFacade::post("/{{$identifier}}/{form}/block/{field_id}/{block_id}/{relation}/{relation_id}", [$controller, "createBlockRelation"])->name("block.relation.store");

        // Relations
        RouteFacade::post("/{{$identifier}}/{form}/{relation}/index", [$controller, "relationIndex"])->name("relation.index");
        RouteFacade::post("/{{$identifier}}/{form}/{relation}", [$controller, "loadRelations"])->name("relation.load");
        RouteFacade::put("/{{$identifier}}/{form}/{relation}/order", [$controller, "orderRelation"])->name("relation.order");
        RouteFacade::delete("/{{$identifier}}/{form}/{relation}/{relation_id}",  [$controller, "destroyRelation"])->name("relation.delete");
        RouteFacade::post("/{{$identifier}}/{form}/{relation}/{relation_id}", [$controller, "createRelation"])->name("relation.store");
    }
}
