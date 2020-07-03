<?php

namespace Fjord\Vue\Page;

use Fjord\Support\VueProp;

class Navigation extends VueProp
{
    /**
     * Components for slot left.
     *
     * @var array
     */
    public $left;

    /**
     * Components for slot right.
     *
     * @var array
     */
    public $right;

    /**
     * Create new Navigation instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->left = new Slot;
        $this->right = new Slot;
    }

    /**
     * Render slot for Vue.
     *
     * @return array
     */
    public function render(): array
    {
        return [
            'left'  => $this->left,
            'right' => $this->right,
        ];
    }
}
