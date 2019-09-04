<?php

namespace AwStudio\Fjord\Form\Concerns;

use AwStudio\Fjord\Form\FormField;
use AwStudio\Fjord\Form\FormFieldCollection;
use Illuminate\Support\Facades\Schema;
use Exception;

trait LoadForms
{
    protected $forms = [];

    protected $currentPath;

    /**
     * Load form for a crud or a form like pages or settings.
     */
    public function load($path, $model)
    {
        $this->currentPath = $path;

        if(array_key_exists($path, $this->forms)) {
            return $this->forms[$path];
        }

        if(! file_exists($path)) {
            return (object) [];
        }

        $form = $this->loadForm(require $path, $model);

        $this->forms[$path] = (object) $form;

        return $this->forms[$path];
    }

    protected function loadForm(array $form, $model)
    {
        $form = $this->getFormLayout($form);
        $form['form_fields'] = $this->getFields($form['form_fields'] ?? [], $model);

        return $form;
    }

    protected function getFormLayout($form)
    {
        if(count($form['form_fields']) < 1) {
            return $form;
        }

        $form_fields = [];

        $form['layout'] = [];
        foreach($form['form_fields'] as $array) {
            if($this->isArrayFormField($array)) {
                $form_fields [] = $array;
                $form['layout'] []= $this->getFormLayoutIds([$array]);
            } else {
                $form_fields = array_merge($array, $form_fields);
                $form['layout'] []= $this->getFormLayoutIds($array);
            }
        }

        $form['form_fields'] = $form_fields;

        return $form;
    }

    protected function getFormLayoutIds($form_fields)
    {
        return collect($form_fields)->pluck('id')->toArray();
    }

    protected function flattenFormFieldsArray($form_fields)
    {

    }
}
