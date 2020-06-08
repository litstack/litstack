<?php

namespace Fjord\Vue\Contracts;

use Closure;

interface AuthorizableContract
{
    /**
     * Set authorize closure.
     *
     * @param Closure $closure
     * @return $this
     */
    public function authorize(Closure $closure);

    /**
     * Check if is authorized.
     *
     * @return boolean
     */
    public function isAuthorized(): bool;
}
