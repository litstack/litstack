<?php

namespace Tests\Crud\Fixtures;

use Ignite\Crud\Config\CrudConfig;
use Ignite\Crud\CrudShow;

class ConfigWithBlockField extends CrudConfig
{
    public $model = DummyLitFormModel::class;

    public function show(CrudShow $page)
    {
        $page->card(function ($form) {
            $form->block('content')->repeatables(function ($rep) {
                $rep->add('text', function ($form) {
                    $form->text('text')->translatable(false);
                });
            });
        });
    }
}
