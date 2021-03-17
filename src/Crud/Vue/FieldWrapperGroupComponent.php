<?php

namespace Ignite\Crud\Vue;

use Ignite\Contracts\Vue\Resizable;
use Ignite\Vue\Component;
use Ignite\Vue\Traits\CanBeResized;

class FieldWrapperGroupComponent extends Component implements Resizable
{
    use CanBeResized;

    /**
     * Before mount lifecycle hook.
     *
     * @return void
     */
    public function beforeMount()
    {
        $this->class('');
        $this->width(12);
    }
}
