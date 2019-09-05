<?php

namespace AwStudio\Fjord\Form;

use Illuminate\Support\Collection;
use AwStudio\Fjord\Support\Facades\FormLoader;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class CrudForm
{
    const DEFAULTS = [
        'form_fields' => [],
        'layout' => [],
        'preview_route' => null,
        'title' => null
    ];

    protected $path;

    protected $model;

    protected $modelInstance;

    protected $originals = [];

    protected $attributes = [];

    protected $form_fields;

    public function __construct($path, $model)
    {
        $attributes = require $path;
        $this->originals = $attributes;
        $this->attributes = $attributes;
        $this->path = $path;

        $this->model = $this->getModelClassName($model);

        $this->modelInstance = with(new $this->model);

        $this->setDefaults();
    }

    protected function getModelClassName($model)
    {
        if(is_string($model)) {
            return $model;
        }

        if($model == EloquentCollection::class) {
            return get_class($model->first());
        }

        return get_class($model);
    }

    protected function setDefaults()
    {
        foreach(self::DEFAULTS as $key => $default) {
            if(! array_key_exists($key, $this->attributes)) {
                $this->attributes[$key] = $default;
            }
        }

        $this->setModel();
        $this->setLayout();
        $this->setFormFields();
        $this->setTitle();
    }

    protected function setTitle()
    {
        if($this->attributes['title']) {
            return;
        }

        $words = explode('_', str_replace('.php', '', basename($this->path)));

        foreach($words as $key => $word) {
            $words[$key] = ucfirst($word);
        }

        $this->attributes['title'] = implode(' ', $words);
    }

    protected function setModel()
    {
        $this->attributes['model'] = $this->model;
    }

    protected function setFormFields()
    {
        $this->form_fields = FormLoader::getFields(
            $this->attributes['form_fields'],
            $this->modelInstance
        );

        unset($this->attributes['form_fields']);
    }

    protected function setLayout()
    {
        if(count($this->attributes['form_fields']) < 1) {
            return;
        }

        $formFields = [];

        foreach($this->attributes['form_fields'] as $array) {
            if($this->isArrayFormField($array)) {
                $formFields [] = $array;
                $this->attributes['layout'] []= $this->getFormLayoutIds([$array]);
            } else {
                $formFields = array_merge($array, $formFields);
                $this->attributes['layout'] []= $this->getFormLayoutIds($array);
            }
        }

        $this->attributes['form_fields'] = $formFields;
    }

    public function setPreviewRoute($model)
    {
        $route = $this->attributes['preview_route'];

        if(is_callable($route)) {
            $this->attributes['preview_route'] = call_user_func($route, $model);
        }
    }

    protected function getFormLayoutIds($formFields)
    {
        return collect($formFields)->pluck('id')->toArray();
    }

    protected function isArrayFormField($array)
    {
        return array_key_exists('type', $array) ? true : false;
    }

    public function getAttribute($key)
    {
        if($key == 'form_fields') {
            return $this->form_fields;
        }

        return $this->attributes[$key] ?? null;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function toArray()
    {
        return $this->getAttributes();
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }
}
