<?php

namespace Ignite\Page\Actions;

use Ignite\Vue\Components\ButtonComponent;

class ButtonSmAction extends BaseAction
{
    /**
     * Create component instance.
     *
     * @return ButtonComponent
     */
    protected function createComponent()
    {
        return (new ButtonComponent)->variant('secondary')->size('sm');
    }
}
