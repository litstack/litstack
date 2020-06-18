<?php

namespace Fjord\Chart;

use Fjord\Config\ConfigHandler;
use Fjord\Chart\Contracts\Engine;
use Fjord\Chart\Loader\BarLoader;
use Fjord\Support\Facades\Config;
use Fjord\Chart\Loader\AreaLoader;
use Fjord\Chart\Loader\ChartLoader;
use Fjord\Chart\Loader\DonutLoader;
use Fjord\Chart\Loader\NumberLoader;
use Fjord\Chart\Loader\ProgressLoader;

class ChartController
{
    /**
     * Get chart data.
     *
     * @param ChartRequest $request
     * @return array
     */
    public function __invoke(ChartRequest $request)
    {
        $config = Config::get($request->key) ?: abort(404);
        $factory = app('fjord.chart.factory');
        $chart = $factory->make($config);
        $engine = $factory->getResolver()->resolve($config->engine);

        $loader = $this->makeLoader($chart->getAttribute('type'), $config, $engine);

        return $loader->get($request);
    }

    /**
     * Make chart loader.
     *
     * @param string $chartType
     * @param ConfigHandler $config
     * @param Engine $engine
     * @return ChartLoader
     */
    protected function makeLoader(string $chartType, ConfigHandler $config, Engine $engine): ChartLoader
    {
        $loader = [
            'donut' => fn () => new DonutLoader($config, $engine),
            'area' => fn () => new AreaLoader($config, $engine),
            'radialBar' => fn () => new ProgressLoader($config, $engine),
            'bar' => fn () => new BarLoader($config, $engine),
            'number' => fn () => new NumberLoader($config, $engine),
        ][$chartType] ?? abort(404);

        return $loader();
    }
}
