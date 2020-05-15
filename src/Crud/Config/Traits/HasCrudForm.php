<?php

namespace Fjord\Crud\Config\Traits;

use Fjord\Crud\CrudForm;

trait HasCrudForm
{
    /**
     * Setup create and edit form.
     *
     * @param \Fjord\Crud\CrudForm $form
     * @return void
     */
    abstract public function form(CrudForm $form);
}
