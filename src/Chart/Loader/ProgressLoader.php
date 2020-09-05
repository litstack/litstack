<?php

namespace Ignite\Chart\Loader;

use Carbon\CarbonInterface;
use Closure;
use Ignite\Chart\ChartSet;

class ProgressLoader extends ChartLoader
{
    use Concerns\HasOneIteration;

    /**
     * Make series.
     *
     * @param CarbonInterface $startTime
     * @param int             $iterations
     * @param Closure         $timeResolver
     * @param Closure         $valueResolver
     * @param Closure         $labelResolver
     *
     * @return array
     */
    protected function makeSeries(
        CarbonInterface $startTime,
        int $iterations,
        Closure $timeResolver,
        Closure $valueResolver,
        Closure $labelResolver
    ): array {
        $query = $this->config->query();

        $set = ChartSet::make($query, $valueResolver, $timeResolver)
            ->label($labelResolver)
            ->iterations(1);

        $set->load($startTime);

        $this->engine->setStart($this->config->start);
        $this->engine->setGoal($this->config->goal);

        return [
            'results' => [],
            'chart'   => $this->engine->render(['Progress'], $set),
        ];
    }
}
