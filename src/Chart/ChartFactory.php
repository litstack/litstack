<?php

namespace Lit\Chart;

use Lit\Chart\Engine\ChartEngineResolver;
use Lit\Config\ConfigHandler;

class ChartFactory
{
    /**
     * Chart engine resolver instance.
     *
     * @var \Lit\Chart\Engine\ChartEngineResolver
     */
    protected $resolver;

    /**
     * Create new ConfigFactory instance..
     *
     * @param \Lit\Chart\Engine\ChartEngineResolver $resolver
     */
    public function __construct(ChartEngineResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Make chart from config.
     *
     * @param ConfigHandler $config
     *
     * @return Chart
     */
    public function make(ConfigHandler $config)
    {
        $engine = $this->resolver->resolve($config->engine);

        $chart = new Chart(
            $engine,
            $config
        );

        $chart->title($config->title);
        $chart->variant($config->variant);

        $config->mount($chart);

        return $chart;
    }

    /**
     * Get engine resolver.
     *
     * @return \Lit\Chart\Engine\ChartEngineResolver
     */
    public function getResolver()
    {
        return $this->resolver;
    }
}
