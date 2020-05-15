<?php

namespace Fjord\Crud\Controllers\Concerns;

use Fjord\Crud\Models\Traits\TrackEdits;
use Illuminate\Support\Facades\Request;

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
}
