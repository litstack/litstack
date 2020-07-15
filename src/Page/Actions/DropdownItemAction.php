<?php

namespace Fjord\Page\Actions;

class DropdownItemAction extends BaseAction
{
    /**
     * Create an action component.
     *
     * @param  string               $action
     * @return \Fjord\Vue\Component
     */
    public function make($title, $action)
    {
        return $this->prepared($title, $action);
    }

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
