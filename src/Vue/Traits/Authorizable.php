<?php

namespace Ignite\Vue\Traits;

use Closure;

trait Authorizable
{
    /**
     * Authorize closure or boolean.
     *
     * @var Closure|bool
     */
    protected $authorizer = true;

    /**
     * Determines wether an authorization has been set.
     *
     * @var bool
     */
    protected $authorizationSet = false;

    /**
     * Set authorize closure.
     *
     * @param  Closure|bool $closure
     * @return $this
     */
    public function authorize($closure)
    {
        $this->authorizer = $closure;

        $this->authorizationSet = true;

        return $this;
    }

    /**
     * Determines wether an authorization has been set.
     *
     * @return bool
     */
    public function authorizationHasBeenSet()
    {
        return $this->authorizationSet;
    }

    /**
     * Check if is authorized.
     *
     * @return bool
     */
    public function check(): bool
    {
        if ($this->authorizer instanceof Closure) {
            return call_user_func($this->authorizer, lit_user());
        }

        return $this->authorizer;
    }
}
