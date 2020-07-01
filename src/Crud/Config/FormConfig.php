<?php

namespace Fjord\Crud\Config;

use Fjord\Crud\Models\FormField;
use Fjord\Support\Facades\Crud;
use Illuminate\Support\Str;

class FormConfig
{
    use Traits\HasCrudShow;

    /**
     * Form field model class.
     *
     * @var string
     */
    public $model = FormField::class;

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
        $this->model = FormField::class;
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
