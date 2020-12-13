<?php

namespace Tests\Crud\Fixtures;

use Ignite\Crud\Config\CrudConfig;
use Ignite\Crud\CrudShow;

class ConfigWithLitRelations extends CrudConfig
{
    public $model = DummyLitFormModel::class;

    public function show(CrudShow $page)
    {
        $page->card(function ($form) {
            $form->manyRelation('posts')->model(DummyPost::class);
            $form->oneRelation('post')->model(DummyPost::class);
        });
    }
}
