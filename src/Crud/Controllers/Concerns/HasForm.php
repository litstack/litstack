<?php

namespace Fjord\Crud\Controllers\Concerns;

use Fjord\Crud\BaseForm;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;
use Fjord\Crud\Models\Traits\TrackEdits;

trait HasForm
{
    /**
     * Get registered form fields.
     *
     * @return array
     */
    public function fields()
    {
        return $this->config->form->getRegisteredFields();
    }

    /**
     * Store model edit.
     *
     * @param string $action
     * @return void
     */
    public function edited($model, $action = 'updated')
    {
        if (!in_array(TrackEdits::class, class_uses($model))) {
            return;
        }

        $model->edited($action);
    }

    /**
     * Get form by name.
     *
     * @param string $form
     * @return void
     */
    protected function getForm(string $formName)
    {
        if (!Str::contains($formName, '-') && $this->formExists($formName)) {
            return $this->config->{$formName};
        }

        if (!$this->formExists($formName)) {
            return;
        }

        $split = explode('-', $formName);

        if (count($split) > 2) {
            abort(404);
        }

        [$baseForm, $subForm] = $split;

        $form = $this->getForm($baseForm);

        if (!$form) {
            return;
        }

        return $form->findField($subForm);
    }

    /**
     * Check if form exists.
     *
     * @param string $formName
     * @return void
     */
    protected function formExists(string $formName)
    {
        if (!Str::contains($formName, '-')) {
            return $this->config->has($formName);
        }

        $split = explode('-', $formName);

        if (count($split) > 2) {
            abort(404);
        }

        [$baseForm, $subForm] = $split;

        $form = $this->getForm($baseForm);

        if (!$form) {
            return false;
        }

        return $form->hasField($subForm);
    }
}
