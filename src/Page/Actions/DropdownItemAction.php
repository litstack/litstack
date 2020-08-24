<?php

namespace Lit\Page\Actions;

class DropdownItemAction extends BaseAction
{
    /**
     * Create component instance.
     *
     * @return \Lit\Vue\Component
     */
    protected function createComponent()
    {
        return component('b-dropdown-item');
    }
}
