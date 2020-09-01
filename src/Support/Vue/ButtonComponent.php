<?php

namespace Ignite\Support\Vue;

use Ignite\Vue\Component;
use Ignite\Vue\Traits\StaticComponentName;
use Illuminate\Support\Str;

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
     * Before mount.
     *
     * @return void
     */
    public function beforeMount()
    {
        $this->variant('secondary');
    }

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

    /**
     * Set button outline.
     *
     * @param  bool  $outline
     * @return $this
     */
    public function outline(bool $outline = true)
    {
        if ($outline) {
            return $this->variant(
                Str::start($this->getProp('variant'), 'outline-')
            );
        }

        return $this->variant(
            preg_replace('/^(?:'.preg_quote('outline-', '/').')+/u', '', $this->getProp('variant'))
        );
    }
}
