<?php

namespace LitApp\Config\Crud;

use Lit\Crud\Config\CrudConfig;
use Lit\Crud\CrudShow;

class BlockInBlockConfig extends CrudConfig
{
    public $model = \Tests\TestSupport\Models\Post::class;

    public $controller = \Tests\TestSupport\Controllers\BlockInBlockController::class;

    public function routePrefix()
    {
        return 'block-in-block';
    }

    public function show(CrudShow $form)
    {
        $form->card(function ($form) {
            // Block.
            $form->block('content')->title('Content')->repeatables(function ($rep) {
                $rep->add('card', function ($form, $preview) {
                    // Nested Block.
                    $form->block('card')->title('Card')->repeatables(function ($rep) {
                        $rep->add('text', function ($form) {
                            $form->text('text')->title('Text');
                        });
                    });
                });
            });
        });
    }
}
