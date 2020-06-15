<?php

namespace Fjord\Chart;

use Closure;
use Fjord\Support\VueProp;
use InvalidArgumentException;
use Fjord\Config\ConfigHandler;
use Fjord\Chart\Contracts\Engine;

class Chart extends VueProp
{
    /**
     * Config handler.
     *
     * @var ConfigHandler
     */
    protected $config;

    /**
     * Chart engine.
     *
     * @var \Fjord\Chart\Contracts\Engine
     */
    protected $engine;

    /**
     * Chart attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Create new Chart instance.
     *
     * @param \Fjord\Chart\Contracts\Engine $engine
     * @param ConfigHandler $config
     */
    public function __construct(Engine $engine, ConfigHandler $config)
    {
        $this->engine = $engine;
        $this->config = $config;

        $this->setAttribute('width', 12);
    }

    /**
     * Get engine.
     *
     * @return \Fjord\Chart\Contracts\Engine
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * Chart variant.
     *
     * @param string $variant
     * @return $this
     */
    public function variant(string $variant)
    {
        $this->setAttribute('variant', $variant);

        return $this;
    }

    /**
     * Chart title.
     *
     * @param string $title
     * @return $this
     */
    public function title(string $title)
    {
        $this->setAttribute('title', $title);

        return $this;
    }

    /**
     * Chart width.
     *
     * @param string $width
     * @return $this
     */
    public function width(string $width)
    {
        $this->setAttribute('width', $width);

        return $this;
    }

    /**
     * Chart engine.
     *
     * @return \Fjord\Vue\Component
     */
    public function component()
    {
        return component('fj-chart')->bind(array_merge(
            ['component' => $this->engine->getComponent()],
            ['chart' => $this]
        ));
    }

    /**
     * Get attribute.
     *
     * @param string $name
     * @return mixed
     */
    public function getAttribute(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Set attribute.
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function setAttribute(string $name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Render for Vue.
     *
     * @return array
     */
    public function render(): array
    {
        return array_merge(
            $this->attributes,
            ['name' => $this->config->getKey()]
        );
    }
}
