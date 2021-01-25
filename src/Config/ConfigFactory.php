<?php

namespace Ignite\Config;

use ReflectionMethod;

class ConfigFactory
{
    /**
     * ConfigHandler instance.
     *
     * @var \Ignite\Config\ConfigHandler
     */
    protected $handler;

    /**
     * Create new ConfigFactory instance.
     *
     * @param  ConfigHandler $handler
     * @return void
     */
    public function __construct(ConfigHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Get alias for the given method.
     *
     * @param  string      $method
     * @return bool|string
     */
    public function getAliasFor(ReflectionMethod $method)
    {
        return false;
    }

    /**
     * Handle config method.
     *
     * @param  Instance $config
     * @param  string   $method
     * @param  array    $parameters
     * @return mixed
     */
    public function handle($method, $parameters, $alias = null)
    {
        $closure = function (...$params) use ($method, $parameters) {
            // Merge parameters coming from the factory and from calling the
            // config attribute function
            $params = array_merge($params, $parameters);

            return $this->handler->getConfig()->$method(...$params);
        };

        if (! is_null($alias)) {
            $method = $alias;
        }

        // Call factory method.
        $attributes = $this->$method($this->handler, $closure);

        // Set attributes.
        $this->handler->setAttribute($method, $attributes);

        return $attributes;
    }
}
