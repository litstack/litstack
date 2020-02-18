<?php

namespace AwStudio\Fjord\Form\Controllers\Traits;

trait CrudShowPreview
{
    protected function addPreviewExtension()
    {
        return ['show.actions' => ['fj-crud-show-preview']];
    }
}
