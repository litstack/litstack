<?php

namespace Ignite\Page\Slots;

use Ignite\Vue\Components\ButtonComponent;

class ButtonSmSlot extends BaseSlot
{
    /**
     * Set action.
     *
     * @param  string          $action
     * @return ButtonComponent
     */
    public function action($action)
    {
        return parent::action($action);
    }

    /**
     * Get action compoent.
     *
     * @return ButtonComponent
     */
    protected function getActionComponent()
    {
        $component = (new ButtonComponent)
            ->variant(Bootstrap::PRIMARY)
            ->outline()
            ->size('sm');

        return $this->component($component);
    }
}
