<?php

namespace Fjord\Crud;

use Fjord\Crud\Api\ApiRepositories;
use Fjord\Crud\Fields\Code;
use Fjord\Crud\Fields\Icon;
use Fjord\Crud\Fields\Input;
use Fjord\Crud\Fields\Modal;
use Fjord\Crud\Fields\Range;
use Fjord\Crud\Fields\Select;
use Fjord\Crud\Fields\Boolean;
use Fjord\Crud\Fields\Wysiwyg;
use Fjord\Crud\Fields\Datetime;
use Fjord\Crud\Fields\Password;
use Fjord\Crud\Fields\Textarea;
use Fjord\Crud\Fields\Component;
use Fjord\Crud\Fields\ListField;
use Fjord\Crud\Fields\Checkboxes;
use Fjord\Crud\Fields\Media\File;
use Fjord\Crud\Fields\Block\Block;
use Fjord\Crud\Fields\Media\Image;
use Illuminate\Foundation\AliasLoader;
use Fjord\Crud\Fields\Relations\OneRelation;
use Fjord\Crud\Fields\Relations\ManyRelation;
use Fjord\Support\Facades\Form as FormFacade;
use Fjord\Crud\Models\Relations\CrudRelations;
use Fjord\Crud\Repositories\ListRepository;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Available fields.
     *
     * @var array
     */
    protected $fields = [
        'input' => Input::class,
        'password' => Password::class,
        'select' => Select::class,
        'boolean' => Boolean::class,
        'code' => Code::class,
        'icon' => Icon::class,
        'datetime' => Datetime::class,
        'dt' => Datetime::class,
        'checkboxes' => Checkboxes::class,
        'range' => Range::class,
        'textarea' => Textarea::class,
        'text' => Textarea::class,
        'wysiwyg' => Wysiwyg::class,
        'block' => Block::class,
        'image' => Image::class,
        'file' => File::class,
        'modal' => Modal::class,
        'component' => Component::class,
        'oneRelation' => OneRelation::class,
        'manyRelation' => ManyRelation::class,
        'list' => ListField::class
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
        $this->app->register(FieldServiceProvider::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('Form', FormFacade::class);

        $this->app->singleton('fjord.form', function () {
            return new Form;
        });

        $this->app['fjord.app']->singleton('crud', new Crud);

        $this->registerFields();

        $this->registerApiRepositories();
    }

    /**
     * Register crud api repositories.
     *
     * @return string
     */
    protected function registerApiRepositories()
    {
        $this->app->singleton(ApiRepositories::class, function () {
            $rep = new ApiRepositories;

            $rep->register('list', ListRepository::class);

            return $rep;
        });
    }

    /**
     * Register fields.
     *
     * @return void
     */
    protected function registerFields()
    {
        foreach ($this->fields as $alias => $field) {
            FormFacade::registerField($alias, $field);
        }
    }
}
