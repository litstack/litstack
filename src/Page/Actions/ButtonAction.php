<?php

namespace Fjord\Page\Actions;

use Fjord\Support\Bootstrap;
use Fjord\Vue\Components\ButtonComponent;

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
