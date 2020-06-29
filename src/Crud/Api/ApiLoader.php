<?php

namespace Fjord\Crud\Api;

use BadMethodCallException;
use Fjord\Crud\BaseForm;
use Illuminate\Support\Str;

class ApiLoader
{
    protected $controller;

    protected $config;

    public function __construct($controller, $config)
    {
        $this->controller = $controller;
        $this->config = $config;
    }

    public function loadForm($type)
    {
        if (!$type) {
            return;
        }

        if (!$this->config->has($type)) {
            return;
        }

        $form = $this->config->{$type};

        if (!$form instanceof BaseForm) {
            return false;
        }

        return $form;
    }

    public function loadField($form, $field_id)
    {
        $field = $form->findFieldOrFail($field_id);

        // if ($this->fieldClass) {
        //     $this->validateFieldInstance($this->field, $this->fieldClass);
        // }

        return $field;
    }

    public function loadModel($id)
    {
        return $this->controller->findOrFail($id);
    }

    protected function callLoadOrFail($method, $parameters)
    {
        $result = $this->{str_replace('OrFail', '', $method)}(...$parameters);

        if (!$result) {
            abort(404);
        }

        return $result;
    }

    public function __call($method, $parameters = [])
    {
        if (Str::endsWith($method, 'OrFail') && Str::startsWith($method, 'load')) {
            return $this->callLoadOrFail($method, $parameters);
        }

        throw new BadMethodCallException(sprintf(
            'Call to undefined method %s::%s()',
            static::class,
            $method
        ));
    }
}
