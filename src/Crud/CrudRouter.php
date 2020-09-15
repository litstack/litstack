<?php

namespace Ignite\Crud;

use Ignite\Application\Navigation\PresetFactory;
use Ignite\Config\ConfigHandler;
use Ignite\Foundation\Litstack;
use Ignite\Routing\Router as LitstackRouter;
use Illuminate\Routing\Router as LaravelRouter;
use Lit\Models\User;

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
                'config' => $config->getKey(),
                'prefix' => "$config->routePrefix",
                'as'     => $config->getKey().'.',
            ], function () use ($config) {
                $this->makeCrudRoutes($config);
                $this->makeFieldRoutes($config);
                $this->setNavigationPreset(
                    $config, ucfirst($config->names['plural'])
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
                'config' => $config->getKey(),
                'prefix' => $config->routePrefix(),
                'as'     => $config->getKey().'.',
            ], function () use ($config) {
                $this->makeFormRoutes($config);
                $this->makeFieldRoutes($config);
                $this->setNavigationPreset(
                    $config, ucfirst($config->names['singular'])
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
            $this->router->post('/{form}', [$config->controller, 'store'])->name('store');
            $this->router->get('/create', [$config->controller, 'create'])->name('create');
            $this->router->get("{{$attribute}}", [$config->controller, 'show'])->name('show');
        }
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
    protected function setNavigationPreset(ConfigHandler $config, $title)
    {
        // Navigation preset will only be created for root crud configs.
        if ($config->has('parent')) {
            return;
        }

        $this->nav->preset($this->getNavigationPresetKeys($config), [
            'link'      => $this->litstack->url($config->routePrefix),
            'title'     => fn ()     => $title,
            'authorize' => function (User $user) use ($config) {
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
