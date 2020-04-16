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

    /**
     * Prepare form config.
     *
     * @return \Fjord\Crud\CrudForm $form
     */
    protected function prepareForm()
    {
        return new CrudForm($this->model);
    }

    /**
     * Resolve form config.
     *
     * @param \Fjord\Crud\CrudForm $form
     * @return $form
     */
    protected function resolveForm(CrudForm $form)
    {
        if (!empty($form->getCard())) {
            $form->card();
        }

        return $form;
    }
}
