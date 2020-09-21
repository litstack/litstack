<?php

namespace Ignite\Support\Vue;

use Ignite\Vue\Component;
use Ignite\Vue\Traits\StaticComponentName;

class DropdownComponent extends ButtonComponent
{
    use StaticComponentName;

    /**
     * Button component name.
     *
     * @var string
     */
    protected $name = 'b-dropdown';

    /**
     * Set dropdown button title.
     *
     * @param  string $title
     * @return $this
     */
    public function text($title)
    {
        return $this->prop('html', $title);
    }

    public function item($component = null)
    {
        if (is_null($component)) {
            $component = DropdownItemComponent::class;
        }

        $this->child(
            $item = component($component)
        );

        return $item;
    }

    public function noCaret()
    {
        return $this->prop('no-caret', true);
    }
}
