<?php

namespace Lit\Page\Actions;

use Lit\Vue\Components\ButtonComponent;

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
