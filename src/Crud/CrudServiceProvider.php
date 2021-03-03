<?php

namespace Ignite\Crud;

use Ignite\Crud\Api\ApiRepositories;
use Ignite\Crud\Config\CrudConfig;
use Ignite\Crud\Config\Factories\CrudConfigFactory;
use Ignite\Crud\Config\Factories\CrudFormConfigFactory;
use Ignite\Crud\Config\Factories\CrudIndexConfigFactory;
use Ignite\Crud\Config\Traits\HasCrudIndex;
use Ignite\Crud\Config\Traits\HasCrudShow;
use Ignite\Crud\Console\CrudCommand;
use Ignite\Crud\Console\FieldCommand;
use Ignite\Crud\Console\FilterCommand;
use Ignite\Crud\Console\FormCommand;
use Ignite\Crud\Console\FormMacroCommand;
use Ignite\Crud\Console\RepeatableCommand;
use Ignite\Crud\Fields\Block\Block;
use Ignite\Crud\Fields\Boolean;
use Ignite\Crud\Fields\Checkboxes;
use Ignite\Crud\Fields\Component;
use Ignite\Crud\Fields\DateTime\Date;
use Ignite\Crud\Fields\DateTime\DateTime;
use Ignite\Crud\Fields\DateTime\Time;
use Ignite\Crud\Fields\Icon;
use Ignite\Crud\Fields\Input;
use Ignite\Crud\Fields\ListField\ListField;
use Ignite\Crud\Fields\Listing;
use Ignite\Crud\Fields\Media\File;
use Ignite\Crud\Fields\Media\Image;
use Ignite\Crud\Fields\Modal;
use Ignite\Crud\Fields\Password;
use Ignite\Crud\Fields\Radio;
use Ignite\Crud\Fields\Range;
use Ignite\Crud\Fields\Relations\ManyRelation;
use Ignite\Crud\Fields\Relations\OneRelation;
use Ignite\Crud\Fields\Route;
use Ignite\Crud\Fields\Route\RouteCollectionResolver;
use Ignite\Crud\Fields\Select;
use Ignite\Crud\Fields\Textarea;
use Ignite\Crud\Fields\Wysiwyg;
use Ignite\Crud\Models\Relations\CrudRelations;
use Ignite\Crud\Repositories\BlockRepository;
use Ignite\Crud\Repositories\DefaultRepository;
use Ignite\Crud\Repositories\ListRepository;
use Ignite\Crud\Repositories\MediaRepository;
use Ignite\Crud\Repositories\ModalRepository;
use Ignite\Crud\Repositories\RelationRepository;
use Ignite\Crud\Repositories\Relations\BelongsToManyRepository;
use Ignite\Crud\Repositories\Relations\BelongsToRepository;
use Ignite\Crud\Repositories\Relations\HasManyRepository;
use Ignite\Crud\Repositories\Relations\HasOneRepository;
use Ignite\Crud\Repositories\Relations\ManyRelationRepository;
use Ignite\Crud\Repositories\Relations\MorphManyRepository;
use Ignite\Crud\Repositories\Relations\MorphOneRepository;
use Ignite\Crud\Repositories\Relations\MorphToManyRepository;
use Ignite\Crud\Repositories\Relations\MorphToRepository;
use Ignite\Crud\Repositories\Relations\OneRelationRepository;
use Ignite\Crud\Vue\FieldWrapperGroupComponent;
use Ignite\Support\Facades\Form as FormFacade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class CrudServiceProvider extends LaravelServiceProvider
{
    /**
     * Vue components to be registered.
     *
     * @var array
     */
    protected $vueComponents = [
        'lit-field-wrapper-group' => FieldWrapperGroupComponent::class,
    ];

    /**
     * Available fields.
     *
     * @var array
     */
    protected $fields = [
        'input'        => Input::class,
        'password'     => Password::class,
        'select'       => Select::class,
        'boolean'      => Boolean::class,
        'icon'         => Icon::class,
        'date'         => Date::class,
        'time'         => Time::class,
        'datetime'     => DateTime::class,
        'dt'           => DateTime::class,
        'checkboxes'   => Checkboxes::class,
        'range'        => Range::class,
        'textarea'     => Textarea::class,
        'text'         => Textarea::class,
        'wysiwyg'      => Wysiwyg::class,
        'block'        => Block::class,
        'image'        => Image::class,
        'file'         => File::class,
        'modal'        => Modal::class,
        'component'    => Component::class,
        'oneRelation'  => OneRelation::class,
        'manyRelation' => ManyRelation::class,
        'list'         => ListField::class,
        'listing'      => Listing::class,
        'radio'        => Radio::class,
        'route'        => Route::class,
    ];

    /**
     * The Commands that should be registered.
     *
     * @var array
     */
    protected $commands = [
        'Crud'       => 'lit.command.crud',
        'Form'       => 'lit.command.form',
        'Field'      => 'lit.command.field',
        'Filter'     => 'lit.command.filters',
        'Repeatable' => 'lit.command.repeatable',
        'FormMacro'  => 'lit.command.form.macro',
    ];

    /**
     * List of config classes with their associated factories.
     *
     * @var array
     */
    protected $configFactories = [
        CrudConfig::class   => CrudConfigFactory::class,
        HasCrudShow::class  => CrudFormConfigFactory::class,
        HasCrudIndex::class => CrudIndexConfigFactory::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register(CrudRelations::class);
        $this->app->register(RouteServiceProvider::class);

        $this->registerVueComponents();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();

        $this->registerForm();

        $this->registerCrud();

        $this->registerApiRepositories();

        $this->registerCrudRouter();

        $this->callAfterResolving('router', function (Router $router) {
            $router->aliasMiddleware('lit.crud', CrudMiddleware::class);
        });
    }

    /**
     * Register the singleton.
     *
     * @return void
     */
    protected function registerCrudRouter()
    {
        $this->app->singleton('lit.crud.router', function ($app) {
            return new CrudRouter(
                $app['router'],
                $app['lit.router'],
                $app['lit.nav'],
                $app['lit']
            );
        });
    }

    /**
     * Register commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        foreach ($this->commands as $command => $abstract) {
            call_user_func_array([$this, "register{$command}Command"], [$abstract]);
        }

        $this->commands(array_values($this->commands));
    }

    /**
     * Register the command.
     *
     * @param  string $abstract
     * @return void
     */
    protected function registerCrudCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new CrudCommand($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @param  string $abstract
     * @return void
     */
    protected function registerFormCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new FormCommand($app['files'], $app['lit']);
        });
    }

    /**
     * Register the command.
     *
     * @param  string $abstract
     * @return void
     */
    protected function registerFieldCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new FieldCommand($app['files'], $app['lit']);
        });
    }

    /**
     * Register the command.
     *
     * @param  string $abstract
     * @return void
     */
    protected function registerFilterCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new FilterCommand($app['files'], $app['lit']);
        });
    }

    /**
     * Register the command.
     *
     * @param  string $abstract
     * @return void
     */
    protected function registerRepeatableCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new RepeatableCommand($app['files'], $app['lit']);
        });
    }

    /**
     * Register the command.
     *
     * @param  string $abstract
     * @return void
     */
    protected function registerFormMacroCommand($abstract)
    {
        $this->app->singleton($abstract, function ($app) {
            return new FormMacroCommand($app['files'], $app['lit']);
        });
    }

    /**
     * Register vue components.
     *
     * @return void
     */
    protected function registerVueComponents()
    {
        $this->callAfterResolving('lit.vue', function ($vue) {
            foreach ($this->vueComponents as $name => $component) {
                $vue->component($name, $component);
            }
        });
    }

    /**
     * Register the singleton.
     *
     * @return void
     */
    protected function registerCrud()
    {
        $this->app->singleton('lit.crud', function ($app) {
            return new Crud($app['lit.router']);
        });
        $this->app->singleton('lit.crud.route.resolver', function () {
            return new RouteCollectionResolver;
        });
    }

    /**
     * Register the singleton.
     *
     * @return void
     */
    protected function registerForm()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('Form', FormFacade::class);

        $this->app->singleton('lit.form', function () {
            $form = new Form();

            $this->registerFields($form);

            return $form;
        });
    }

    /**
     * Register crud api repositories.
     *
     * @return string
     */
    protected function registerApiRepositories()
    {
        $this->app->singleton(ApiRepositories::class, function () {
            $rep = new ApiRepositories();

            $rep->register('default', DefaultRepository::class);
            $rep->register('list', ListRepository::class);
            $rep->register('block', BlockRepository::class);
            $rep->register('media', MediaRepository::class);
            $rep->register('modal', ModalRepository::class);
            $rep->register('relation', RelationRepository::class);
            $rep->register('one-relation', OneRelationRepository::class);
            $rep->register('many-relation', ManyRelationRepository::class);
            $rep->register('belongs-to', BelongsToRepository::class);
            $rep->register('belongs-to-many', BelongsToManyRepository::class);
            $rep->register('has-many', HasManyRepository::class);
            $rep->register('has-one', HasOneRepository::class);
            $rep->register('morph-one', MorphOneRepository::class);
            $rep->register('morph-to-many', MorphToManyRepository::class);
            $rep->register('morph-to', MorphToRepository::class);
            $rep->register('morph-many', MorphManyRepository::class);

            return $rep;
        });
    }

    /**
     * Register fields.
     *
     * @param  Form $field
     * @return void
     */
    protected function registerFields(Form $form)
    {
        foreach ($this->fields as $alias => $field) {
            $form->field($alias, $field);
        }
    }
}
