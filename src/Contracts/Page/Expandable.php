<?php

namespace Ignite\Contracts\Page;

interface Expandable
{
    /**
     * Expand html container of the page to maximum width.
     *
     * @param  bool  $expand
     * @return $this
     */
    public function expand(bool $expand = true);
}
