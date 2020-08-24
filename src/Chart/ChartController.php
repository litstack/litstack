<?php

namespace Lit\Chart;

use Lit\Chart\Contracts\Engine;
use Lit\Chart\Loader\AreaLoader;
use Lit\Chart\Loader\BarLoader;
use Lit\Chart\Loader\ChartLoader;
use Lit\Chart\Loader\DonutLoader;
use Lit\Chart\Loader\NumberLoader;
use Lit\Chart\Loader\ProgressLoader;
use Lit\Config\ConfigHandler;
use Lit\Support\Facades\Config;

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

        return $loader->get($request);
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
        $loader = [
            'donut'     => fn () => new DonutLoader($config, $engine),
            'area'      => fn () => new AreaLoader($config, $engine),
            'radialBar' => fn () => new ProgressLoader($config, $engine),
            'bar'       => fn () => new BarLoader($config, $engine),
            'number'    => fn () => new NumberLoader($config, $engine),
        ][$chartType] ?? abort(404);

        return $loader();
    }
}
