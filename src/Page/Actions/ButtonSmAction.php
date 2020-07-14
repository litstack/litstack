<?php

namespace Fjord\Page\Actions;

use Fjord\Vue\Components\ButtonComponent;

class ButtonSmAction extends BaseAction
{
    /**
     * Create an action component.
     *
     * @param  string          $action
     * @return ButtonComponent
     */
    public function make($title, $action)
    {
        return $this->prepared($title, $action)
            ->variant('outline-primary')
            ->size('sm');
    }

    /**
     * Create component instance.
     *
     * @return ButtonComponent
     */
    protected function createComponent()
    {
        return new ButtonComponent;
    }
}
