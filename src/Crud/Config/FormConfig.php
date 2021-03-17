<?php

namespace Ignite\Crud\Config;

use Ignite\Crud\Models\Form;
use Ignite\Support\Facades\Config;
use Ignite\Support\Facades\Form as FormFacade;
use Illuminate\Support\Str;

class FormConfig
{
    use Traits\HasCrudShow,
        Concerns\ManagesActions,
        Concerns\ManagesPermissions;

    /**
     * Form field model class.
     *
     * @var string
     */
    public $model = Form::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller;

    /**
     * Load model.
     *
     * @return Form|null
     */
    public static function load()
    {
        $config = Config::get(static::class);

        $model = FormFacade::load($config->collection, $config->formName);

        if (! $model) {
            $model = Form::firstOrCreate([
                'config_type' => static::class,
            ], [
                'form_name'  => $config->formName,
                'collection' => $config->collection,
                'form_type'  => 'show',
            ]);
        }

        return $model;
    }

    /**
     * Create new FormConfig instance.
     */
    public function __construct()
    {
        $this->model = Form::class;
    }

    /**
     * Form collection name.
     *
     * @return string
     */
    public function collection()
    {
        $split = explode(
            '\\',
            last(explode('Config\\Form\\', static::class))
        );

        return strtolower($split[0]);
    }

    /**
     * Form name.
     *
     * @return string
     */
    public function formName()
    {
        return Str::snake(str_replace('Config', '', class_basename(static::class)));
    }

    /**
     * Form route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        $collection = $this->collection();
        $formName = $this->formName();

        return "form/{$collection}/{$formName}";
    }

    /**
     * Form permissions for read and update.
     *
     * @return array
     */
    public function permissions(): array
    {
        return [
            'read'   => $this->authorize(lit_user(), 'read'),
            'update' => $this->authorize(lit_user(), 'update'),
        ];
    }

    /**
     * Form singular name.
     *
     * @return array
     */
    public function names()
    {
        return [
            'singular' => $this->title(),
        ];
    }

    /**
     * The form title.
     *
     * @return string
     */
    public function title()
    {
        return ucfirst(Str::singular($this->formName()));
    }

    /**
     * Form component.
     *
     * @param Component $component
     *
     * @return void
     */
    public function component($component)
    {
        //
    }
}
