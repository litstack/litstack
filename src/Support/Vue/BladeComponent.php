<?php

namespace Ignite\Support\Vue;

use Ignite\Vue\Component;
use Illuminate\Contracts\View\View;

class BladeComponent extends Component
{
    /**
     * Set blade wrapper.
     *
     * @param  Component|string $component
     * @return Component
     */
    public function wrapper($component)
    {
        $this->prop('wrapper', $wrapper = component($component));

        return $wrapper;
    }

    /**
     * Set wrapper width.
     *
     * @param  float|int $width
     * @return $this
     */
    public function width($width)
    {
        $this->wrapper('lit-col')->prop('width', $width);

        return $this;
    }

    /**
     * Get array.
     *
     * @return array
     */
    public function render(): array
    {
        if ($this->props['view'] instanceof View) {
            $this->props['view'] = $this->props['view']->render();
        }

        return parent::render();
    }
}
