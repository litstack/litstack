<?php

namespace FjordApp\Config\Crud;

use Fjord\Crud\CrudForm;
use Fjord\Vue\Crud\CrudTable;
use Fjord\Crud\Config\CrudConfig;
use Fjord\Crud\Fields\Blocks\Repeatables;
use Illuminate\Database\Eloquent\Builder;

class PostConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = \FjordTest\TestSupport\Models\Post::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = \FjordTest\TestSupport\Controllers\PostController::class;

    /**
     * Index table search keys.
     *
     * @var array
     */
    public $search = ['title'];

    /**
     * Index table sort by default.
     *
     * @var string
     */
    public $sortByDefault = 'id.desc';

    /**
     * Model singular and plural name.
     *
     * @return array
     */
    public function names()
    {
        return [
            'singular' => 'Article',
            'plural' => 'Articles',
        ];
    }

    /**
     * Sort by keys.
     *
     * @return array
     */
    public function sortBy()
    {
        return [
            'id.desc' => __f('fj.sort_new_to_old'),
            'id.asc' => __f('fj.sort_old_to_new'),
        ];
    }

    /**
     * Initialize index query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder $query
     */
    public function indexQuery(Builder $query)
    {
        // $query->with('relation');

        return $query;
    }

    /**
     * Index table filter groups.
     *
     * @return array
     */
    public function filter()
    {
        return [];
    }

    /**
     * Build index table.
     *
     * @param \Fjord\Vue\Crud\CrudTable $table
     * @return void
     */
    public function index(CrudTable $table)
    {
        $table->col('title')
            ->value('{title}')
            ->sortBy('title');
    }

    /**
     * Setup create and edit form.
     *
     * @param \Fjord\Crud\CrudForm $form
     * @return void
     */
    public function form(CrudForm $form)
    {
        $form->card(function ($form) {
            $this->mainCard($form);
        });
    }

    /**
     * Define form sections in methods to keep the overview.
     *
     * @param \Fjord\Crud\CrudForm $form
     * @return void
     */
    protected function mainCard(CrudForm $form)
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

        $form->blocks('content')
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

        $form->blocks('media_blocks')
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
