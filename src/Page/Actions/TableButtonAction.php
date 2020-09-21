<?php

namespace Ignite\Page\Actions;

use Ignite\Page\Table\Components\ButtonComponent;

class TableButtonAction extends BaseAction
{
    /**
     * Create component instance.
     *
     * @return ButtonComponent
     */
    protected function createComponent()
    {
        return (new ButtonComponent)->size('sm')->variant('outline-primary');
    }
}
