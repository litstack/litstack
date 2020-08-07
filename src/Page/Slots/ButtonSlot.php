<?php

namespace Fjord\Page\Slots;

use Fjord\Support\Bootstrap;
use Fjord\Vue\Components\ButtonComponent;

class ButtonSlot extends BaseSlot
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
            ->outline();

        return $this->component($component);
    }
}
