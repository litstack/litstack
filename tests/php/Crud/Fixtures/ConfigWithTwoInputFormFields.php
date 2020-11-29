<?php

namespace Tests\Crud\Fixtures;

use Ignite\Crud\CrudShow;
use Ignite\Crud\Config\CrudConfig;

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
