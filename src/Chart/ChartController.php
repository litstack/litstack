<?php

namespace Ignite\Chart;

use Ignite\Chart\Contracts\Engine;
use Ignite\Chart\Loader\ChartLoader;
use Ignite\Config\ConfigHandler;
use Ignite\Support\Facades\Config;
use InvalidArgumentException;

class ChartController
{
    /**
     * Get chart data.
     *
     * @param ChartRequest $request
     *
     * @return array
     */
    public function __invoke(ChartRequest $request)
    {
        $config = Config::get($request->key) ?: abort(404);
        $factory = app('lit.chart.factory');
        $chart = $factory->make($config);
        $engine = $factory->getResolver()->resolve($config->engine);

        $loader = $this->makeLoader($chart->getAttribute('type'), $config, $engine);

        return $loader->get($request->type ?: abort(404));
    }

    /**
     * Make chart loader.
     *
     * @param string        $chartType
     * @param ConfigHandler $config
     * @param Engine        $engine
     *
     * @return ChartLoader
     */
    protected function makeLoader(string $chartType, ConfigHandler $config, Engine $engine): ChartLoader
    {
        try {
            return app('lit.chart.loader.resolver')->resolve($chartType, $config, $engine);
        } catch (InvalidArgumentException $e) {
            abort(404, debug($e->getMessage()));
        }
    }
}
