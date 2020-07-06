<?php

namespace Fjord\Config;

class ConfigFactory
{
    /**
     * ConfigHandler instance.
     *
     * @var \Fjord\Config\ConfigHandler
     */
    protected $handler;

    /**
     * Create new ConfigFactory instance.
     *
     * @param ConfigHandler $handler
     */
    public function __construct(ConfigHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Handle config method.
     *
     * @param Instance $config
     * @param string   $method
     * @param array    $parameters
     *
     * @return mixed
     */
    public function handle($method, $parameters)
    {
        $closure = function (...$params) use ($method, $parameters) {
            // Merge parameters coming from the factory and from calling the
            // config attribute function
            $params = array_merge($params, $parameters);

            return $this->handler->getConfig()->$method(...$params);
        };

        // Call factory method.
        $attributes = $this->$method($this->handler, $closure);

        // Set attributes.
        $this->handler->setAttribute($method, $attributes);

        return $attributes;
    }
}
