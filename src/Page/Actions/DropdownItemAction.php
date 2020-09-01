<?php

namespace Ignite\Page\Actions;

class DropdownItemAction extends BaseAction
{
    /**
     * Create component instance.
     *
     * @return \Ignite\Vue\Component
     */
    protected function createComponent()
    {
        return component('b-dropdown-item');
    }
}
