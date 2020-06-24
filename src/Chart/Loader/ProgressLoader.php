<?php

namespace Fjord\Chart\Loader;

use Closure;
use Fjord\Chart\ChartSet;
use Carbon\CarbonInterface;

class ProgressLoader extends ChartLoader
{
    use Concerns\HasOneIteration;

    /**
     * Make series.
     *
     * @param CarbonInterface $startTime
     * @param integer $iterations
     * @param Closure $timeResolver
     * @param Closure $valueResolver
     * @param Closure $labelResolver
     * @return array
     */
    protected function makeSeries(
        CarbonInterface $startTime,
        int $iterations,
        Closure $timeResolver,
        Closure $valueResolver,
        Closure $labelResolver
    ): array {
        $query = $this->config->model::query();

        $set = ChartSet::make($query, $valueResolver, $timeResolver)
            ->label($labelResolver)
            ->iterations(1);

        $set->load($startTime);

        $this->engine->setMinMax($this->config->minValue, $this->config->maxValue);

        return [
            'results' => [],
            'chart' => $this->engine->render(['Progress'], $set)
        ];
    }
}
