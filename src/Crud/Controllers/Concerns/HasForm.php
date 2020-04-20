<?php

namespace Fjord\Crud\Controllers\Concerns;

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
}
