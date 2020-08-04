<?php

namespace Fjord\Page\Actions;

class DropdownItemAction extends BaseAction
{
    /**
     * Create component instance.
     *
     * @return \Fjord\Vue\Component
     */
    protected function createComponent()
    {
        return component('b-dropdown-item');
    }
}
