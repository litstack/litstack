<?php

namespace Lit\Crud;

use Lit\Contracts\Page\Action;
use Lit\Page\Table\Table;

class CrudIndexTable extends Table
{
    /**
     * Add action.
     *
     * @param  string $title
     * @param  string $action
     * @return $this
     */
    public function action($title, $action)
    {
        parent::action($title, $action);

        last($this->actions)->on('run', RunCrudActionEvent::class);

        return $this;
    }
}
