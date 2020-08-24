<?php

namespace Lit\Crud;

use Lit\Crud\Api\ApiRepositories;
use Lit\Crud\Fields\Block\Block;
use Lit\Crud\Fields\Boolean;
use Lit\Crud\Fields\Checkboxes;
use Lit\Crud\Fields\Component;
use Lit\Crud\Fields\Datetime;
use Lit\Crud\Fields\Icon;
use Lit\Crud\Fields\Input;
use Lit\Crud\Fields\ListField\ListField;
use Lit\Crud\Fields\Media\File;
use Lit\Crud\Fields\Media\Image;
use Lit\Crud\Fields\Modal;
use Lit\Crud\Fields\Password;
use Lit\Crud\Fields\Radio;
use Lit\Crud\Fields\Range;
use Lit\Crud\Fields\Relations\ManyRelation;
use Lit\Crud\Fields\Relations\OneRelation;
use Lit\Crud\Fields\Route;
use Lit\Crud\Fields\Route\RouteCollectionResolver;
use Lit\Crud\Fields\Select;
use Lit\Crud\Fields\Textarea;
use Lit\Crud\Fields\Wysiwyg;
use Lit\Crud\Models\Relations\CrudRelations;
use Lit\Crud\Repositories\BlockRepository;
use Lit\Crud\Repositories\DefaultRepository;
use Lit\Crud\Repositories\ListRepository;
use Lit\Crud\Repositories\MediaRepository;
use Lit\Crud\Repositories\ModalRepository;
use Lit\Crud\Repositories\RelationRepository;
use Lit\Crud\Repositories\Relations\BelongsToManyRepository;
use Lit\Crud\Repositories\Relations\BelongsToRepository;
use Lit\Crud\Repositories\Relations\HasManyRepository;
use Lit\Crud\Repositories\Relations\HasOneRepository;
use Lit\Crud\Repositories\Relations\ManyRelationRepository;
use Lit\Crud\Repositories\Relations\MorphManyRepository;
use Lit\Crud\Repositories\Relations\MorphOneRepository;
use Lit\Crud\Repositories\Relations\MorphToManyRepository;
use Lit\Crud\Repositories\Relations\MorphToRepository;
use Lit\Crud\Repositories\Relations\OneRelationRepository;
use Lit\Support\Facades\Form as FormFacade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
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
        'datetime'     => Datetime::class,
        'dt'           => Datetime::class,
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
        'radio'        => Radio::class,
        'route'        => Route::class,
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
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerForm();

        $this->registerCrud();

        $this->registerApiRepositories();
    }

    /**
     * Register the singleton.
     *
     * @return void
     */
    protected function registerCrud()
    {
        $this->callAfterResolving('lit.app', function ($app) {
            $app->singleton('crud', function () {
                return new Crud;
            });

            $app->singleton('crud.route.resolver', function () {
                return new RouteCollectionResolver;
            });
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
