<?php

namespace Ignite\Page\Actions;

use Ignite\Support\Bootstrap;
use Ignite\Support\Vue\ButtonComponent;

class ButtonAction extends BaseAction
{
    /**
     * Create component instance.
     *
     * @return ButtonComponent
     */
    protected function createComponent()
    {
        return (new ButtonComponent)->variant(Bootstrap::PRIMARY);
    }
}
