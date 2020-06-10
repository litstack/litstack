<?php

namespace Fjord\Crud\Controllers\Concerns;

use Fjord\Crud\Field;
use Fjord\Crud\BaseForm;
use Fjord\Crud\Fields\Block\Block;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;
use Fjord\Crud\Models\Traits\TrackEdits;
use Fjord\Crud\Fields\Traits\FieldHasForm;

trait ManagesForm
{
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
     * Check if formName is subform.
     *
     * @param string $formName
     * @return boolean
     */
    protected function isSubForm(string $formName)
    {
        return Str::contains($formName, '-');
    }

    /**
     * Get form by name.
     *
     * @param string $form
     * @return void
     */
    protected function getForm(string $formName)
    {
        if (!$this->formExists($formName)) {
            return;
        }

        if (!$this->isSubForm($formName)) {
            return $this->config->{$formName};
        }

        $partials = explode('-', $formName);
        $baseFormName = array_shift($partials);

        if (!$baseForm = $this->getForm($baseFormName)) {
            return;
        }

        return $baseForm->getForm(...$partials);
    }

    /**
     * Check if form exists.
     *
     * @param string $formName
     * @return void
     */
    protected function formExists(string $formName)
    {
        if (!$this->isSubForm($formName)) {
            return $this->config->has($formName);
        }

        $partials = explode('-', $formName);
        $baseFormName = array_shift($partials);

        if (!$baseForm = $this->getForm($baseFormName)) {
            return false;
        }

        return $baseForm->hasForm(...$partials);
    }

    /**
     * Does field has form.
     *
     * @param mixed $field
     * @return boolean
     */
    protected function fieldHasForm(Field $field)
    {
        return method_exists($field, 'form') ?: $field instanceof Block;
    }
}
