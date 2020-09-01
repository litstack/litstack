<?php

namespace Ignite\Vue\Traits;

use Closure;

trait Authorizable
{
    /**
     * Authorize closure.
     *
     * @var Closure
     */
    protected $authorizeClosure;

    /**
     * Set authorize closure.
     *
     * @param  Closure $closure
     * @return $this
     */
    public function authorize(Closure $closure)
    {
        $this->authorizeClosure = $closure;

        return $this;
    }

    /**
     * Check if is authorized.
     *
     * @return bool
     */
    public function isAuthorized(): bool
    {
        if (! $this->authorizeClosure) {
            return true;
        }

        $closure = $this->authorizeClosure;

        return $closure(lit_user());
    }
}
