<?php

namespace Fjord\Crud\Config\Traits;

use Fjord\Crud\CrudForm;

trait HasForm
{
    /**
     * Setup create and edit form.
     *
     * @param \Fjord\Crud\CrudForm $form
     * @return void
     */
    abstract protected function form(CrudForm $form);
}
