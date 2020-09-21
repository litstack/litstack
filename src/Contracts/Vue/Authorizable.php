<?php

namespace Ignite\Contracts\Vue;

use Closure;

interface Authorizable
{
    /**
     * Set authorize closure.
     *
     * @param Closure $closure
     *
     * @return $this
     */
    public function authorize(Closure $closure);

    /**
     * Check if is authorized.
     *
     * @return bool
     */
    public function check(): bool;
}
