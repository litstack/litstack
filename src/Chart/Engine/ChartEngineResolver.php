<?php

namespace Ignite\Chart\Engine;

use Closure;
use InvalidArgumentException;

class ChartEngineResolver
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
    public function register(string $engine, Closure $closure)
    {
        unset($this->resolved[$engine]);

        $this->resolvers[$engine] = $closure;
    }

    /**
     * Resolve an engine instance by name.
     *
     * @param string $engine
     *
     * @return void
     */
    public function resolve(string $engine)
    {
        if (isset($this->resolved[$engine])) {
            return $this->resolved[$engine];
        }

        if (isset($this->resolvers[$engine])) {
            return $this->resolved[$engine] = call_user_func($this->resolvers[$engine]);
        }

        throw new InvalidArgumentException("Chart engine [{$engine}] not found.");
    }
}
