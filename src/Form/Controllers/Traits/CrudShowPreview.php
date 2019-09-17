<?php

namespace AwStudio\Fjord\Form\Controllers\Traits;

trait CrudShowPreview
{
    protected function addPreviewExtension()
    {
        return ['show.actions' => ['crud-show-preview']];
    }
}
