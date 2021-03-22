<?php

namespace Ignite\Crud;

use Closure;
use Ignite\Application\Navigation\PresetFactory;
use Ignite\Config\ConfigHandler;
use Ignite\Contracts\Crud\CrudCreate;
use Ignite\Contracts\Crud\CrudUpdate;
use Ignite\Foundation\Litstack;
use Ignite\Routing\Router as LitstackRouter;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Routing\Router as LaravelRouter;

class CrudRouter
{
    /**
     * Laravel router instance.
     *
     * @var LaravelRouter
     */
    protected $router;

    /**
     * Listack router instance.
     *
     * @var LitstackRouter
     */
    protected $litstackRouter;

    /**
     * Navigation preset factory instance.
     *
     * @var PresetFactory
     */
    protected $nav;

    /**
     * Litstack instance.
     *
     * @var Litstack
     */
    protected $litstack;

    /**
     * Create new CrudRouter instance.
     *
     * @param  LaravelRouter  $router
     * @param  LitstackRouter $litstackRouter
     * @param  PresetFactory  $nav
     * @param  Litstack       $litstack
     * @return void
     */
    public function __construct(LaravelRouter $router, LitstackRouter $litstackRouter, PresetFactory $nav, Litstack $litstack)
    {
        $this->router = $router;
        $this->litstackRouter = $litstackRouter;
        $this->litstack = $litstack;
        $this->nav = $nav;
    }

    /**
     * Generate crud routes for the given config.
     *
     * @param  ConfigHandler $config
     * @return void
     */
    public function routes(ConfigHandler $config)
    {
        $this->litstackRouter->group(function () use ($config) {
            $this->router->group([
                'config' => $config->getNamespace(),
                'prefix' => "$config->routePrefix",
                'as'     => $config->getKey().'.',
            ], function () use ($config) {
                $this->makeCrudRoutes($config);
                $this->makeFieldRoutes($config);
                $this->setNavigationPreset(
                    $config, fn () => ucfirst($config->names['plural'])
                );
            });
        });
    }

    /**
     * Generate form routes.
     *
     * @param  ConfigHandler $config
     * @return void
     */
    public function formRoutes(ConfigHandler $config)
    {
        $this->litstackRouter->group(function () use ($config) {
            $this->router->group([
                'config' => $config->getNamespace(),
                'prefix' => $config->routePrefix(),
                'as'     => $config->getKey().'.',
            ], function () use ($config) {
                $this->makeFormRoutes($config);
                $this->makeFieldRoutes($config);
                $this->setNavigationPreset(
                    $config, fn () => ucfirst($config->names['singular'])
                );
            });
        });
    }

    /**
     * Get route attribute.
     *
     * @param  ConfigHandler $config
     * @return string
     */
    protected function getRouteAttribute(ConfigHandler $config)
    {
        return str_replace('.', '_', $config->getKey());
    }

    /**
     * Make form routes.
     *
     * @param  ConfigHandler $config
     * @return void
     */
    protected function makeFormRoutes(ConfigHandler $config)
    {
        $this->router->get('/', [$config->controller, 'show'])->name('show');
    }

    /**
     * Make crud routes.
     *
     * @param  ConfigHandler $config
     * @return void
     */
    public function makeCrudRoutes(ConfigHandler $config)
    {
        $attribute = $this->getRouteAttribute($config);

        $this->router->post('/order', [$config->controller, 'order'])->name('order');
        $this->router->delete('/{id}', [$config->controller, 'destroy'])->name('destroy');

        // Index routes.
        if ($config->has('index')) {
            $this->router->get('/', [$config->controller, 'index'])->name('index');
            $this->router->post('/index', [$config->controller, 'indexTable'])->name('index.items');
        }

        // Show routes.
        if ($config->has('show')) {
            $this->makeCreateRoutes($config);
            $this->makeUpdateRoutes($config);
        }

        foreach ($config->getAlias() as $alias => $method) {
            if ($method !== 'show') {
                continue;
            }

            $this->makeCreateRoutes($config, $alias);
            $this->makeUpdateRoutes($config, $alias);

            // $this->router->get("{{$attribute}}/{$alias}", [$config->controller, 'show'])->name($alias);
        }
    }

    /**
     * Make update routes.
     *
     * @param  ConfigHandler $config
     * @param  string        $method
     * @return void
     */
    public function makeUpdateRoutes(ConfigHandler $config, $method = 'show')
    {
        if (! $config->methodNeeds($method, CrudUpdate::class, $pos = 0)) {
            return;
        }

        $attribute = $this->getRouteAttribute($config);
        $suffix = $config->getRouteSuffix($method);
        $uri = rtrim("{{$attribute}}/{$suffix}", '\//');

        $route = $this->router->get($uri, [$config->controller, 'show'])->name($method);
    }

    /**
     * Make crud create routes.
     *
     * @param  ConfigHandler $config
     * @param  string        $method
     * @return void
     */
    protected function makeCreateRoutes(ConfigHandler $config, $method = 'show')
    {
        if (! $config->methodNeeds($method, CrudCreate::class, $pos = 0)) {
            return;
        }

        $suffix = $config->getRouteSuffix($method);
        $uri = ltrim("{$suffix}/create", '/');

        $this->router->post('/{form}', [$config->controller, 'store'])->name('store');
        $this->router->get($uri, [$config->controller, 'create'])->name("create.{$method}");
    }

    /**
     * Make field routes.
     *
     * @param  ConfigHandler $config
     * @return void
     */
    protected function makeFieldRoutes(ConfigHandler $config)
    {
        $attribute = $this->getRouteAttribute($config);
        $this->router->post('/run-action/{key}', [$config->controller, 'runAction']);
        // Api
        $this->router->any('/api/{form_type}/{repository?}/{method?}/{child_method?}', [$config->controller, 'api'])->name('api');
        $this->router->any('/{id}/api/{form_type}/{repository?}/{method?}/{child_method?}', [$config->controller, 'api'])->name('api');

        // List
        $this->router->get("/{{$attribute}}/{form}/list/{field_id}", [$config->controller, 'loadListItems'])->name('list.index');
        $this->router->get("/{{$attribute}}/{form}/list/{field_id}/{list_item_id}", [$config->controller, 'loadListItem'])->name('list.load');
    }

    /**
     * Set the navigation preset for the given crud config.
     *
     * @param  ConfigHandler $config
     * @return void
     */
    protected function setNavigationPreset(ConfigHandler $config, Closure $title)
    {
        // Navigation preset will only be created for root crud configs.
        if ($config->has('parent')) {
            return;
        }

        $this->nav->preset($this->getNavigationPresetKeys($config), [
            'link'  => $this->litstack->url($config->routePrefix),
            'title' => $title,
            'badge' => function () use ($config) {
                return $config->has('badge') ? $config->badge() : null;
            },
            'authorize' => function (Authorizable $user) use ($config) {
                return $config->authorize($user, 'read');
            },
        ]);
    }

    /**
     * Get config navigation preset keys.
     *
     * @param  ConfigHandler $config
     * @return void
     */
    protected function getNavigationPresetKeys(ConfigHandler $config)
    {
        return [$config->getNamespace(), $config->getKey()];
    }
}
