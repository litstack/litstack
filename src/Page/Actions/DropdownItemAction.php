<?php

namespace Ignite\Page\Actions;

use Ignite\Support\Vue\DropdownItemComponent;

class DropdownItemAction extends BaseAction
{
    /**
     * Create component instance.
     *
     * @return \Ignite\Vue\Component
     */
    protected function createComponent()
    {
        return (new DropdownItemComponent)->size('sm');
    }
}
