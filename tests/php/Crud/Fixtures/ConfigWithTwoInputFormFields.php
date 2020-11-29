<?php

namespace Tests\Crud\Fixtures;

use Ignite\Crud\Config\CrudConfig;
use Ignite\Crud\CrudShow;

class ConfigWithTwoInputFormFields extends CrudConfig
{
    public $model = DummyLitFormModel::class;

    public function show(CrudShow $page)
    {
        $page->card(function ($form) {
            $form->input('foo')->translatable(false);
            $form->input('bar')->translatable(false);
        });
    }
}
