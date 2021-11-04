<?php

namespace Tests\Traits;

use Ignite\Crud\RouteServiceProvider;
use Ignite\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Lit\Config\Crud\PostConfig;

trait InteractsWithCrud
{
    use CreateLitUsers;

    /**
     * Get crud route.
     *
     * @param  string  $route
     * @return void
     */
    public function getCrudRoute(string $route)
    {
        return lit()->url(strip_slashes(
            Config::get($this->config ?? PostConfig::class)->route_prefix."/{$route}"
        ));
    }

    /**
     * Set up Crud.
     *
     * @return void
     */
    public function setUpCrud()
    {
        $this->publishCrudConfig();
        $this->refreshCrudConfig();
        $this->makeCrudRoutes();
        $this->migrate();
    }

    /**
     * Make crud routes.
     *
     * @return void
     */
    public function makeCrudRoutes()
    {
        $provider = new RouteServiceProvider($this->app);

        $this->callUnaccessibleMethod($provider, 'mapCrudRoutes');
    }

    /**
     * Publish crud config.
     *
     * @return void
     */
    public function publishCrudConfig()
    {
        if (File::exists(base_path('lit/app/Config/Crud/PostConfig.php'))) {
            return;
        }

        if (! File::exists(base_path('lit/app/Config/Crud'))) {
            File::makeDirectory(base_path('lit/app/Config/Crud'));
        }

        File::copy(__DIR__.'/../TestSupport/Config/PostConfig.php', base_path('lit/app/Config/Crud/PostConfig.php'));
        File::copy(__DIR__.'/../TestSupport/Config/BlockInBlockConfig.php', base_path('lit/app/Config/Crud/BlockInBlockConfig.php'));
    }

    /**
     * Refresh Crud config.
     *
     * @return void
     */
    public function refreshCrudConfig()
    {
        File::copy(__DIR__.'/../TestSupport/Config/PostConfig.php', base_path('lit/app/Config/Crud/PostConfig.php'));
    }
}
