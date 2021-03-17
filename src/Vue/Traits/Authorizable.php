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
     * Set authorize closure.
     *
     * @param  Closure|bool $closure
     * @return $this
     */
    public function authorize($closure)
    {
        $this->authorizer = $closure;

        return $this;
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
