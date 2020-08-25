<?php

namespace Ignite\Page;

use Ignite\Page\Actions\ButtonAction;
use Ignite\Page\Actions\ButtonSmAction;
use Ignite\Page\Actions\DropdownItemAction;
use Ignite\Support\VueProp;

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
    public function __construct($page)
    {
        $this->left = new Slot($page, new ButtonSmAction);
        $this->right = new Slot($page, new ButtonAction);
        $this->controls = new Slot($page, new DropdownItemAction);
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
