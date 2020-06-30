<?php

namespace Fjord\Support;

use RuntimeException;

abstract class FjordFacade
{
    /**
     * Get the registered name of the binding.
     *
     * @return string
     */
    abstract protected static function getFacadeAccessor();

    /**
     * @return instance|null
     */
    protected static function getInstance()
    {
        return app()->get('fjord.app')->get(static::getFacadeAccessor());
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param string $method
     * @param array  $args
     *
     * @throws \RuntimeException
     *
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::getInstance();

        if (!$instance) {
            throw new RuntimeException('A Fjord facade root has not been set.');
        }

        return $instance->$method(...$args);
    }
}
