<?php

namespace Fjord\Page\Slots;

use Fjord\Page\Components\ButtonComponent;

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
        $component = (new ButtonComponent)->variant('outline-primary');

        return $this->component($component)    
    }
}
