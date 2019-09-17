<?php

namespace AwStudio\Fjord\Form\Controllers\Traits;

trait CrudShowForm
{
    protected function addFormExtension()
    {
        return ['show.content' => ['crud-show-form']];
    }
}
