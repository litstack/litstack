<?php

namespace FjordApp\Config\Crud;

use Fjord\Crud\Config\CrudConfig;
use Fjord\Crud\CrudShow;
use Fjord\Vue\Crud\CrudTable;

class PostConfig extends CrudConfig
{
    public $model = \FjordTest\TestSupport\Models\Post::class;

    public $controller = \FjordTest\TestSupport\Controllers\PostController::class;

    public function names()
    {
        return [
            'singular' => 'Article',
            'plural'   => 'Articles',
        ];
    }

    public function index(CrudTable $container)
    {
        $container->table(function ($table) {
            $table->col('title')
                ->value('{title}')
                ->sortBy('title');
        })
            ->sortByDefault('id.desc')
            ->search('name')
            ->sortBy([
                'id.desc' => __f('fj.sort_new_to_old'),
                'id.asc'  => __f('fj.sort_old_to_new'),
            ])
            ->width(12);
    }

    public function show(CrudShow $form)
    {
        $form->card(function ($form) {
            $this->mainCard($form);
        });
    }

    protected function mainCard(CrudShow $form)
    {
        $form->input('title')
            ->updateRules('max:15')
            ->creationRules('required')
            ->rules('min:2')
            ->title('Title')
            ->width(6);

        $form->textarea('text')
            ->title('Text');

        $form->image('test_image')
            ->title('Image')
            ->maxFiles(1);

        $form->image('test_images')
            ->title('Images')
            ->maxFiles(2);

        $form->list('test_list')
            ->title('Test List')
            ->form(function ($form) {
                $form->input('test_list_input')->title('Hi');
            });
        //->nestable();

        $form->block('content')
            ->title('Content')
            ->repeatables(function ($rep) {
                $rep->add('text', function ($form) {
                    $form->text('text')
                        ->title('Title')
                        ->rules('min:5');
                });

                $rep->add('input', function ($form) {
                    $form->input('title')
                        ->title('Title')
                        ->rules('min:5');
                });
            });

        $form->block('media_repeatables')
            ->title('Media')
            ->repeatables(function ($rep) {
                $rep->add('image', function ($form) {
                    $form->image('images')
                        ->title('Images')
                        ->maxFiles(2);
                });
            });
    }
}
