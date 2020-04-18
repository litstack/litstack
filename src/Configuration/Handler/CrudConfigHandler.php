<?php

namespace Fjord\Configuration\Handler;

use Closure;
use App\Models\Article;
use Fjord\Crud\CrudForm;
use Fjord\Vue\Crud\CrudTable;

class CrudConfigHandler
{
    /**
     * Resolve query.
     *
     * @param Instance $config
     * @param Closure $method
     * @return Builder
     */
    public function query($config, Closure $method)
    {
        $query = Article::query();

        $method($query);

        return $query;
    }

    /**
     * Setup create and edit form.
     *
     * @param Instance $config
     * @param Closure $method
     * @return \Fjord\Crud\CrudForm
     */
    public function form($config, Closure $method)
    {
        $form = new CrudForm($config->model);

        $method($form);

        if (!empty($form->getCard())) {
            $form->card();
        }

        return $form;
    }

    /**
     * Setup index table.
     *
     * @param Instance $config
     * @param Closure $method
     * @return \Fjord\Vue\Crud\CrudTable
     */
    protected function index($config, Closure $method)
    {
        $table = new CrudTable($config->model);

        $method($table);

        return $table;
    }
}
