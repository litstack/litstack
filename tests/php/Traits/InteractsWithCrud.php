<?php

namespace FjordTest\Traits;

use Fjord\Crud\RouteServiceProvider;
use Illuminate\Support\Facades\File;

trait InteractsWithCrud
{
    use CreateFjordUsers;

    /**
     * Get crud route.
     *
     * @param string $route
     * @return void
     */
    public function getCrudRoute(string $route)
    {
        return fjord()->url(strip_slashes("/crud/posts/{$route}"));
    }

    /**
     * Set up Crud.
     *
     * @return void
     */
    public function setUpCrud()
    {
        $this->publishCrudConfig();
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
        if (File::exists(base_path('fjord/app/Config/Crud/PostConfig.php'))) {
            return;
        }

        if (!File::exists(base_path('fjord/app/Config/Crud'))) {
            File::makeDirectory(base_path('fjord/app/Config/Crud'));
        }

        File::copy(__DIR__ . '/../TestSupport/Config/PostConfig.php', base_path('fjord/app/Config/Crud/PostConfig.php'));
    }

    /**
     * Refresh Crud config.
     *
     * @return void
     */
    public function refreshCrudConfig()
    {
        File::copy(__DIR__ . '/../TestSupport/Config/PostConfig.php', base_path('fjord/app/Config/Crud/PostConfig.php'));
    }
}
