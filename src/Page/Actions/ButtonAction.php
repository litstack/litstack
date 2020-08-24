<?php

namespace Lit\Page\Actions;

use Lit\Support\Bootstrap;
use Lit\Vue\Components\ButtonComponent;

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
