<?php

namespace Ignite;

use Ignite\Application\Application;
use Ignite\Application\Navigation\Config as NavigationConfig;
use Ignite\Application\Navigation\NavigationConfigFactory;
use Ignite\Application\Package\LitPackage as Package;
use Ignite\Crud\Config\Factories\CrudFormConfigFactory;
use Ignite\Crud\Config\Factories\CrudIndexConfigFactory;
use Ignite\Crud\Config\Traits\HasCrudIndex;
use Ignite\Crud\Config\Traits\HasCrudShow;
use Ignite\Page\Table\Components\ImageComponent;
use Ignite\Page\Table\Components\RelationComponent;
use Ignite\Page\Table\Components\ToggleComponent;
use Ignite\Vue\Components\BladeComponent;
use Ignite\Vue\Components\FieldWrapperCardComponent;
use Ignite\Vue\Components\FieldWrapperComponent;
use Ignite\Vue\Components\FieldWrapperGroupComponent;
use Ignite\Vue\Components\InfoComponent;

class LitPackage extends Package
{
    /**
     * List of service providers to be registered for this package.
     *
     * @var array
     */
    protected $providers = [
        \Ignite\Config\ConfigServiceProvider::class,
        \Ignite\Translation\TranslationServiceProvider::class,
        \Ignite\Application\ApplicationRouteServiceProvider::class,
        \Ignite\Permissions\PermissionsServiceProvider::class,
        \Ignite\Vue\VueServiceProvider::class,
        \Ignite\Chart\ServiceProvider::class,
        \Ignite\Crud\ServiceProvider::class,
        \Ignite\User\ServiceProvider::class,
    ];

    /**
     * List of all artisan commands to be registered for this package.
     *
     * @var array
     */
    protected $commands = [
        Commands\LitNav::class,
        Commands\LitAdmin::class,
        Commands\LitUser::class,
        Commands\LitCrud::class,
        Commands\LitForm::class,
        Commands\LitChart::class,
        Commands\LitAction::class,
        Commands\LitExtend::class,
        Commands\LitController::class,
        Commands\LitComponents::class,
        Commands\LitDefaultPermissions::class,
    ];

    /**
     * List of extendable root Vue components this package contains.
     *
     * @var array
     */
    protected $components = [
        'lit-info'                => InfoComponent::class,
        'lit-blade'               => BladeComponent::class,
        'lit-field-wrapper'       => FieldWrapperComponent::class,
        'lit-field-wrapper-card'  => FieldWrapperCardComponent::class,
        'lit-field-wrapper-group' => FieldWrapperGroupComponent::class,
        'lit-col-image'           => ImageComponent::class,
        'lit-col-toggle'          => ToggleComponent::class,
        'lit-col-crud-relation'   => RelationComponent::class,
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
