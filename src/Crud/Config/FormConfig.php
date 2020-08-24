<?php

namespace Lit\Crud\Config;

use Illuminate\Support\Str;
use Lit\Crud\Models\Form;
use Lit\Support\Facades\Crud;

class FormConfig
{
    use Traits\HasCrudShow;

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
    public function permissions()
    {
        return [
            'read'   => Crud::authorize($this->controller, 'read'),
            'update' => Crud::authorize($this->controller, 'update'),
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
            'singular' => ucfirst(Str::singular($this->formName())),
        ];
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
