<?php

namespace Fjord;

use Fjord\Config\Traits\HasIndex;
use Fjord\Application\Application;
use Fjord\Vue\Components\ColComponent;
use Fjord\Vue\Components\CardComponent;
use Fjord\Vue\Components\InfoComponent;
use Fjord\Crud\Config\Traits\HasCrudForm;
use Fjord\User\Components\UsersComponent;
use Fjord\Crud\Config\Traits\HasCrudIndex;
use Fjord\Crud\Components\CrudFormComponent;
use Fjord\Crud\Components\CrudIndexComponent;
use Fjord\User\Config\UserIndexConfigFactory;
use Fjord\Config\Factories\IndexConfigFactory;
use Fjord\Vue\Components\FieldWrapperComponent;
use Fjord\Vue\Components\Index\ColImageComponent;
use Fjord\Vue\Components\Index\ColToggleComponent;
use Fjord\Vue\Components\FieldWrapperCardComponent;
use Fjord\Vue\Components\FieldWrapperGroupComponent;
use Fjord\Application\Package\FjordPackage as Package;
use Fjord\Crud\Config\Factories\CrudFormConfigFactory;
use Fjord\Crud\Config\Factories\CrudIndexConfigFactory;
use Fjord\Vue\Components\Index\ColCrudRelationComponent;
use Fjord\Application\Navigation\NavigationConfigFactory;
use Fjord\Application\Navigation\Config as NavigationConfig;

class FjordPackage extends Package
{
    /**
     * List of service providers to be registered for this package.
     * 
     * @var array
     */
    protected $providers = [
        \Fjord\Application\Translation\TranslationServiceProvider::class,
        \Fjord\Application\ApplicationRouteServiceProvider::class,
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
        Commands\FjordComponents::class,
        Commands\FjordDefaultPermissions::class,
    ];

    /**
     * List of extendable root Vue components this package contains.
     * 
     * @var array
     */
    protected $components = [
        // Root
        'fj-users' => UsersComponent::class,
        'fj-crud-index' => CrudIndexComponent::class,
        'fj-crud-form' => CrudFormComponent::class,

        // Other
        'fj-info' => InfoComponent::class,
        'fj-field-wrapper' => FieldWrapperComponent::class,
        'fj-field-wrapper-card' => FieldWrapperCardComponent::class,
        'fj-field-wrapper-group' => FieldWrapperGroupComponent::class,
        'fj-col-image' => ColImageComponent::class,
        'fj-col-toggle' => ColToggleComponent::class,
        'fj-col-crud-relation' => ColCrudRelationComponent::class
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
        HasCrudForm::class => CrudFormConfigFactory::class,
        HasCrudIndex::class => CrudIndexConfigFactory::class,
    ];

    /**
     * Boot application.
     *
     * @param Application $app
     * @return void
     */
    public function boot(Application $app)
    {
        //
    }
}
