<?php

namespace Fjord\Crud\Config;

use Illuminate\Support\Str;
use Fjord\Crud\Models\FormField;
use Fjord\Support\Config as FjordConfig;

abstract class FormConfig extends FjordConfig
{
    use Traits\HasForm;

    /**
     * Form field model class.
     *
     * @var string
     */
    protected $model;

    /**
     * Controller class.
     *
     * @var string
     */
    protected $controller;

    /**
     * Form collection name.
     *
     * @var string
     */
    protected $collection;

    /**
     * Form name, is used for routing.
     *
     * @var string
     */
    protected $formName;

    /**
     * Create new FormConfig instance.
     */
    public function __construct()
    {
        $split = explode(
            '\\',
            last(explode('Form\\', static::class))
        );
        $this->collection = strtolower($split[0]);

        $this->model = FormField::class;
    }

    /**
     * Get crud route prefix.
     *
     * @return string $route
     */
    public function route_prefix()
    {
        $collection = strtolower($this->collection);
        $formName = strtolower($this->formName);
        return "form/{$collection}/{$formName}";
    }

    public function permissions()
    {
        return [
            'read' => true,
            'update' => true,
        ];
    }

    /**
     * Model singular and plural name.
     *
     * @return array $names
     */
    public function names()
    {
        return [
            'singular' => ucfirst(Str::singular($this->formName)),
            'plural' => ucfirst(Str::plural($this->formName)),
        ];
    }

    /**
     * Get preview route.
     *
     * @return string|null
     */
    protected function previewRoute()
    {
        return null;
    }
}
