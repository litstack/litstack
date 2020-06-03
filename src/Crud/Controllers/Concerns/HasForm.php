<?php

namespace Fjord\Crud\Controllers\Concerns;

use Fjord\Crud\BaseForm;
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
    public function getForm(string $formName)
    {
        if (!$this->formExists($formName)) {
            return;
        }

        $this->config->{$formName};
    }

    /**
     * Check if form exists.
     *
     * @param string $formName
     * @return void
     */
    public function formExists(string $formName)
    {
        return $this->config->has($formName);
    }
}
