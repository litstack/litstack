<?php

namespace Ignite\Page\Traits;

trait Expandable
{
    /**
     * Expand container.
     *
     * @param  bool  $expand
     * @return $this
     */
    public function expand(bool $expand = true)
    {
        $this->setAttribute('expand', $expand);

        return $this;
    }
}
