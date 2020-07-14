<?php

namespace Fjord\Page;

use Fjord\Page\Actions\ButtonAction;
use Fjord\Page\Actions\DropdownItemAction;
use Fjord\Support\VueProp;

class Navigation extends VueProp
{
    /**
     * Slot "left".
     *
     * @var Slot
     */
    protected $left;

    /**
     * Slot "right".
     *
     * @var Slot
     */
    protected $right;

    /**
     * Slot "controls".
     *
     * @var Slot
     */
    protected $controls;

    /**
     * Create new Navigation instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->left = new Slot(new ButtonAction);
        $this->right = new Slot(new ButtonAction);
        $this->controls = new Slot(new DropdownItemAction);
    }

    /**
     * Get slot right.
     *
     * @return Slot
     */
    public function getRightSlot()
    {
        return $this->right;
    }

    /**
     * Get slot left.
     *
     * @return Slot
     */
    public function getLeftSlot()
    {
        return $this->left;
    }

    /**
     * Get controls slot.
     *
     * @return Slot
     */
    public function getControlsSlot()
    {
        return $this->controls;
    }

    /**
     * Render slot for Vue.
     *
     * @return array
     */
    public function render(): array
    {
        return [
            'left'     => $this->left,
            'right'    => $this->right,
            'controls' => $this->controls,
        ];
    }
}
