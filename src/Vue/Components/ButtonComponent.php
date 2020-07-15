<?php

namespace Fjord\Vue\Components;

use Fjord\Vue\Component;
use Fjord\Vue\Traits\StaticComponentName;

class ButtonComponent extends Component
{
    use StaticComponentName;

    /**
     * Button component name.
     *
     * @var string
     */
    protected $name = 'b-button';

    /**
     * Set button variant.
     *
     * @param  string $variant
     * @return $this
     */
    public function variant($variant)
    {
        return $this->prop('variant', $variant);
    }

    /**
     * Set button size.
     *
     * @param  string $size
     * @return $this
     */
    public function size($size)
    {
        return $this->prop('size', $size);
    }
}
