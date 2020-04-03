<?php

namespace AwStudio\Fjord;

use AwStudio\Fjord\Application\Application;
use AwStudio\Fjord\User\Components\UsersComponent;
use AwStudio\Fjord\Application\Package\FjordPackage as Package;
use AwStudio\Fjord\Form\Vue\Components\CrudIndexComponent;

class FjordPackage extends Package
{
    /**
     * List of service providers to be registered for this package.
     * 
     * @var array
     */
    protected $providers = [
        \AwStudio\Fjord\Application\Translation\TranslationServiceProvider::class,
        \AwStudio\Fjord\Application\RouteServiceProvider::class,
        \AwStudio\Fjord\User\ServiceProvider::class,
        \AwStudio\Fjord\Form\ServiceProvider::class,
    ];

    /**
     * List of all artisan commands to be registered for this package.
     *
     * @var array
     */
    protected $commands = [
        Commands\FjordAdmin::class,
        Commands\FjordUser::class,
        Commands\FjordCrud::class,
        Commands\FjordExtend::class,
        Commands\FjordExtension::class,
        Commands\FjordDefaultPermissions::class
    ];

    /**
     * List of components this package contains.
     * 
     * @var array
     */
    protected $components = [
        'fj-users' => UsersComponent::class,
        'fj-crud-index' => CrudIndexComponent::class
    ];

    /**
     * List of handlers for config files.
     * 
     * @var array
     */
    protected $configHandler = [
        'navigation.main' => \AwStudio\Fjord\Application\Navigation\MainConfig::class,
        'navigation.topbar' => \AwStudio\Fjord\Application\Navigation\TopbarConfig::class,
        'users.table' => User\Config\TableConfig::class
    ];

    /**
     * Boot application.
     *
     * @param Application $app
     * @return void
     */
    public function boot(Application $app)
    {
        $app->addCssFile('/' . config('fjord.route_prefix') . '/css/app.css');

        foreach (config('fjord.assets.css') as $path) {
            $this->app['fjord']->addCssFile($path);
        }
    }
}
