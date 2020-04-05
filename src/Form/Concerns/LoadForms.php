<?php

namespace Fjord\Form\Concerns;

use Exception;
use Fjord\Form\Form;
use Fjord\Form\FormField;
use Fjord\Form\CrudForm;
use Fjord\Support\NestedCollection;
use Illuminate\Support\Facades\Schema;

trait LoadForms
{
    protected $forms = [];

    protected $currentPath;

    /**
     * Load crud form array from path.
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

        $form = new CrudForm($path, $model);

        $this->forms[$path] = (object) $form;

        return $this->forms[$path];
    }

    protected function loadForm(array $form, $model)
    {
        $form = $this->getFormLayout($form);
        $form = $this->getPreviewRoute($form, $model);
        $form['form_fields'] = $this->getFields($form['form_fields'] ?? [], $model);

        return $form;
    }

    protected function getPreviewRoute($form, $model)
    {
        if(! array_key_exists('preview_route', $form)) {
            return $form;
        }

        if(is_callable($form['preview_route'])) {
            $form['preview_route'] = $form['preview_route']($model);
        }

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
