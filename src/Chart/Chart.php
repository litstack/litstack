<?php

namespace Ignite\Chart;

use Ignite\Chart\Contracts\Engine;
use Ignite\Config\ConfigHandler;
use Ignite\Support\VueProp;

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
     * @var \Ignite\Chart\Contracts\Engine
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
     * @param \Ignite\Chart\Contracts\Engine $engine
     * @param ConfigHandler                  $config
     */
    public function __construct(Engine $engine, ConfigHandler $config)
    {
        $this->engine = $engine;
        $this->config = $config;
        $this->setAttribute('type', $this->getTypeFromConfig());
        $this->setAttribute('width', 12);
    }

    /**
     * Get config type.
     *
     * @return string
     */
    protected function getTypeFromConfig()
    {
        return $this->config->type;
    }

    /**
     * Get engine.
     *
     * @return \Ignite\Chart\Contracts\Engine
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * Chart variant.
     *
     * @param  string $variant
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
     * @param  string $title
     * @return $this
     */
    public function title(string $title)
    {
        $this->setAttribute('title', $title);

        return $this;
    }

    /**
     * Value prefix.
     *
     * @param  string $prefix
     * @return $this
     */
    public function prefix(string $prefix)
    {
        $this->setAttribute('prefix', $prefix);

        return $this;
    }

    /**
     * Value suffix.
     *
     * @param string $suffix
     *
     * @return $this
     */
    public function suffix(string $suffix)
    {
        $this->setAttribute('suffix', $suffix);

        return $this;
    }

    /**
     * Value format.
     *
     * @param string $format
     *
     * @return $this
     */
    public function format(string $format)
    {
        $this->setAttribute('format', $format);

        return $this;
    }

    /**
     * Value currency format.
     *
     * @param string|bool $currency
     *
     * @return $this
     */
    public function currency(string $currency = '')
    {
        if ($currency == '') {
            $currency = '$';
        }

        $this->format('0,0.00')->suffix(" {$currency}");

        return $this;
    }

    /**
     * Chart width.
     *
     * @param string $width
     *
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
     * @return \Ignite\Vue\Component
     */
    public function component()
    {
        return component('lit-chart')->bind(array_merge(
            ['component' => $this->engine->getComponent()],
            ['chart' => $this]
        ));
    }

    /**
     * Set chart height.
     *
     * @param string|int $height
     *
     * @return $this
     */
    public function height($height)
    {
        $this->setAttribute('height', $height);

        return $this;
    }

    /**
     * Get attribute.
     *
     * @param string $name
     *
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
     * @param mixed  $value
     *
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
            [
                'name'    => $this->config->getKey(),
                'goal'    => $this->config->has('goal') ? $this->config->goal : null,
                'compare' => $this->config->has('compare') ? $this->config->compare : false,
            ]
        );
    }
}
