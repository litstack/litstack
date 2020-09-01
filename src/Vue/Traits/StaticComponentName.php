<?php

namespace Ignite\Vue\Traits;

trait StaticComponentName
{
    /**
     * Create new Component instance.
     *
     * @param  string $name
     * @return void
     */
    public function __construct(string $name = null)
    {
        $this->beforeMount();
    }
}
