<?php

namespace Fjord\Chart;

use Carbon\CarbonInterface;
use Fjord\Support\Facades\Config;
use Fjord\Chart\Loader\AreaLoader;
use Fjord\Chart\Loader\DonutLoader;
use Fjord\Chart\Loader\ProgressLoader;

class ChartController
{
    /**
     * Get chart data.
     *
     * @param ChartRequest $request
     * @return array
     */
    public function get(ChartRequest $request)
    {
        $config = Config::get($request->key) ?: abort(404);
        $factory = app('fjord.chart.factory');
        $chart = $factory->make($config);
        $engine = $factory->getResolver()->resolve($config->engine);

        $loader = $this->makeLoader($chart->getAttribute('type'), $config, $engine);

        return $loader->get($request);
    }

    protected function makeLoader(string $chartType, $config, $engine)
    {
        $loader = [
            'donut' => fn () => new DonutLoader($config, $engine),
            'area' => fn () => new AreaLoader($config, $engine),
            'radialBar' => fn () => new ProgressLoader($config, $engine),
        ][$chartType] ?? abort(404);

        return $loader();
    }
}
