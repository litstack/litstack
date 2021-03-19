<?php

namespace Ignite\Chart\Loader;

use Closure;
use Ignite\Chart\Contracts\Engine;
use Ignite\Config\ConfigHandler;
use InvalidArgumentException;

class ChartLoaderResolver
{
    /**
     * The array of engine resolvers.
     *
     * @var array
     */
    protected $resolvers = [];

    /**
     * The resolved engine instances.
     *
     * @var array
     */
    protected $resolved = [];

    /**
     * Register a new chart engine resolver.
     *
     * @param string  $engine
     * @param Closure $closure
     *
     * @return void
     */
    public function register(string $type, Closure $closure)
    {
        unset($this->resolved[$type]);

        $this->resolvers[$type] = $closure;
    }

    /**
     * Resolve an engine instance by name.
     *
     * @param  ConfigHandler $config
     * @param  Engine        $engine
     * @return void
     */
    public function resolve($type, ConfigHandler $config, Engine $engine)
    {
        if (isset($this->resolved[$type])) {
            return $this->resolved[$type];
        }

        if (isset($this->resolvers[$type])) {
            return $this->resolved[$type] = call_user_func(
                $this->resolvers[$type], $config, $engine
            );
        }

        throw new InvalidArgumentException("Chart loader [{$type}] not found.");
    }
}
