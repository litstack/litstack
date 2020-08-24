<?php

namespace Lit;

use Lit\Application\Application;
use Lit\Application\Navigation\Config as NavigationConfig;
use Lit\Application\Navigation\NavigationConfigFactory;
use Lit\Application\Package\LitPackage as Package;
use Lit\Crud\Config\Factories\CrudFormConfigFactory;
use Lit\Crud\Config\Factories\CrudIndexConfigFactory;
use Lit\Crud\Config\Traits\HasCrudIndex;
use Lit\Crud\Config\Traits\HasCrudShow;
use Lit\Page\Table\Components\ImageComponent;
use Lit\Page\Table\Components\RelationComponent;
use Lit\Page\Table\Components\ToggleComponent;
use Lit\Vue\Components\BladeComponent;
use Lit\Vue\Components\FieldWrapperCardComponent;
use Lit\Vue\Components\FieldWrapperComponent;
use Lit\Vue\Components\FieldWrapperGroupComponent;
use Lit\Vue\Components\InfoComponent;

class LitPackage extends Package
{
    /**
     * List of service providers to be registered for this package.
     *
     * @var array
     */
    protected $providers = [
        \Lit\Config\ConfigServiceProvider::class,
        \Lit\Translation\TranslationServiceProvider::class,
        \Lit\Application\ApplicationRouteServiceProvider::class,
        \Lit\Permissions\PermissionsServiceProvider::class,
        \Lit\Vue\VueServiceProvider::class,
        \Lit\Chart\ServiceProvider::class,
        \Lit\Crud\ServiceProvider::class,
        \Lit\User\ServiceProvider::class,
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
