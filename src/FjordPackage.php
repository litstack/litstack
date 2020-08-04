<?php

namespace Fjord;

use Fjord\Application\Application;
use Fjord\Application\Navigation\Config as NavigationConfig;
use Fjord\Application\Navigation\NavigationConfigFactory;
use Fjord\Application\Package\FjordPackage as Package;
use Fjord\Crud\Config\Factories\CrudFormConfigFactory;
use Fjord\Crud\Config\Factories\CrudIndexConfigFactory;
use Fjord\Crud\Config\Traits\HasCrudIndex;
use Fjord\Crud\Config\Traits\HasCrudShow;
use Fjord\Page\Table\Components\ImageComponent;
use Fjord\Page\Table\Components\RelationComponent;
use Fjord\Page\Table\Components\ToggleComponent;
use Fjord\Vue\Components\BladeComponent;
use Fjord\Vue\Components\FieldWrapperCardComponent;
use Fjord\Vue\Components\FieldWrapperComponent;
use Fjord\Vue\Components\FieldWrapperGroupComponent;
use Fjord\Vue\Components\InfoComponent;

class FjordPackage extends Package
{
    /**
     * List of service providers to be registered for this package.
     *
     * @var array
     */
    protected $providers = [
        \Fjord\Config\ConfigServiceProvider::class,
        \Fjord\Translation\TranslationServiceProvider::class,
        \Fjord\Application\ApplicationRouteServiceProvider::class,
        \Fjord\Permissions\PermissionsServiceProvider::class,
        \Fjord\Vue\VueServiceProvider::class,
        \Fjord\Chart\ServiceProvider::class,
        \Fjord\Crud\ServiceProvider::class,
        \Fjord\User\ServiceProvider::class,
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
        Commands\FjordChart::class,
        Commands\FjordAction::class,
        Commands\FjordExtend::class,
        Commands\FjordController::class,
        Commands\FjordComponents::class,
        Commands\FjordDefaultPermissions::class,
    ];

    /**
     * List of extendable root Vue components this package contains.
     *
     * @var array
     */
    protected $components = [
        'fj-info'                => InfoComponent::class,
        'fj-blade'               => BladeComponent::class,
        'fj-field-wrapper'       => FieldWrapperComponent::class,
        'fj-field-wrapper-card'  => FieldWrapperCardComponent::class,
        'fj-field-wrapper-group' => FieldWrapperGroupComponent::class,
        'fj-col-image'           => ImageComponent::class,
        'fj-col-toggle'          => ToggleComponent::class,
        'fj-col-crud-relation'   => RelationComponent::class,
    ];

    /**
     * List of config classes with their associated factories.
     *
     * @var array
     */
    protected $configFactories = [
        // Navigation
        NavigationConfig::class => NavigationConfigFactory::class,

        // Crud
        HasCrudShow::class  => CrudFormConfigFactory::class,
        HasCrudIndex::class => CrudIndexConfigFactory::class,
    ];

    /**
     * Boot application.
     *
     * @param  Application $app
     * @return void
     */
    public function boot(Application $app)
    {
        //
    }
}
