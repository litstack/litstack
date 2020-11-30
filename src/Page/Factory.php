<?php

namespace Ignite\Page;

use Closure;

class Factory
{
    /**
     * Page extensions.
     *
     * @var array
     */
    protected $extensions = [];

    /**
     * Extend the given crud config.
     *
     * @param  string  $alias
     * @param  Closure $closure
     * @return void
     */
    public function extend($alias, Closure $closure)
    {
        if (is_array($alias)) {
            $alias = implode('@', $alias);
        }

        if (! array_key_exists($alias, $this->extensions)) {
            $this->extensions[$alias] = [];
        }

        $this->extensions[$alias][] = $closure;
    }

    /**
     * Get extensions for the given alias.
     *
     * @param  string $alias
     * @param  string $name
     * @return array
     */
    public function getExtensions($alias, $name = null)
    {
        if (is_array($alias)) {
            $alias = implode('@', $alias);
        } elseif (! is_null($name)) {
            $alias .= '@'.$name;
        }

        return $this->extensions[$alias] ?? [];
    }
}
