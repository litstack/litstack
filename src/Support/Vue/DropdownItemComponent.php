<?php

namespace Ignite\Support\Vue;

use Ignite\Vue\Component;
use Ignite\Vue\Traits\StaticComponentName;

class DropdownItemComponent extends ButtonComponent
{
    use StaticComponentName;

    /**
     * Button component name.
     *
     * @var string
     */
    protected $name = 'b-dropdown-item';
}
