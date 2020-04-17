<?php

namespace Fjord;

use Fjord\Application\Application;
use Fjord\User\Components\UsersComponent;
use Fjord\Crud\Vue\Components\CrudShowComponent;
use Fjord\Crud\Vue\Components\CrudIndexComponent;
use Fjord\Application\Package\FjordPackage as Package;

class FjordPackage extends Package
{
    /**
     * List of service providers to be registered for this package.
     * 
     * @var array
     */
    protected $providers = [
        \Fjord\Application\Translation\TranslationServiceProvider::class,
        \Fjord\Application\RouteServiceProvider::class,
        \Fjord\User\ServiceProvider::class,
        \Fjord\Crud\ServiceProvider::class,
    ];

    /**
     * List of all artisan commands to be registered for this package.
     *
     * @var array
     */
    protected $commands = [
        Commands\FjordNav::class,
        Commands\FjordAdmin::class,
        Commands\FjordUser::class,
        Commands\FjordCrud::class,
        Commands\FjordForm::class,
        Commands\FjordExtend::class,
        Commands\FjordExtension::class,
        Commands\FjordDefaultPermissions::class,
    ];

    /**
     * List of components this package contains.
     * 
     * @var array
     */
    protected $components = [
        'fj-users' => UsersComponent::class,
        'fj-crud-index' => CrudIndexComponent::class,
        'fj-crud-show' => CrudShowComponent::class,
    ];

    /**
     * List of handlers for config files.
     * 
     * @var array
     */
    protected $configHandler = [
        'navigation.main' => \Fjord\Application\Navigation\MainConfig::class,
        'navigation.topbar' => \Fjord\Application\Navigation\TopbarConfig::class,
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
            $app->addCssFile($path);
        }
    }
}
