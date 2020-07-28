<?php

namespace FjordApp\Config\Crud;

use Fjord\Crud\Config\CrudConfig;
use Fjord\Crud\CrudShow;

class BlockInBlockConfig extends CrudConfig
{
    public $model = \FjordTest\TestSupport\Models\Post::class;

    public $controller = \FjordTest\TestSupport\Controllers\BlockInBlockController::class;

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
