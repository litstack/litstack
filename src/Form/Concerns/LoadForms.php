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
        $form['form_fields'] = $this->getFields($form['form_fields'] ?? [], $model);

        return $form;
    }
}
