<?php

namespace Fjord\Page\Slots;

class DropdownButtonSlot extends BaseSlot
{
    /**
     * Get action compoent.
     *
     * @return Fjord\Vue\Component
     */
    protected function getActionComponent()
    {
        return $this->component('fj-page-action');
    }
}
