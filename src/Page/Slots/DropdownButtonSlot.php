<?php

namespace Lit\Page\Slots;

class DropdownButtonSlot extends BaseSlot
{
    /**
     * Get action compoent.
     *
     * @return Lit\Vue\Component
     */
    protected function getActionComponent()
    {
        return $this->component('fj-page-action');
    }
}
