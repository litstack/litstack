<?php

namespace Ignite\Vue\Traits;

trait CanBeResized
{
    /**
     * Set component width.
     *
     * @param  int|float $width
     * @return $this
     */
    public function width($width)
    {
        return $this->prop('width', $width);
    }
}
