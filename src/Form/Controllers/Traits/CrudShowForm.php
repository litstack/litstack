<?php

namespace Fjord\Form\Controllers\Traits;

trait CrudShowForm
{
    protected function addFormExtension()
    {
        return ['show.content' => ['fj-crud-show-form']];
    }
}
