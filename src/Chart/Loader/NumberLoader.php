<?php

namespace Ignite\Chart\Loader;

use Carbon\CarbonInterface;
use Closure;
use Ignite\Chart\ChartSet;
use Illuminate\Support\Arr;

class NumberLoader extends ChartLoader
{
    use Concerns\HasOneIteration;
    use Concerns\HasComparison;

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
        $nextTimeResolver = $this->getNextTimeResolver();

        $query = $this->config->query();

        $set = ChartSet::make($query, $valueResolver, $timeResolver)
            ->label($labelResolver)
            ->iterations(1);

        $set->load($startTime);

        if ($this->config->compare) {
            $set->load($nextTimeResolver($startTime));
        }

        return [
            'results' => Arr::flatten($set->getValues()),
            'value'   => $set->getValues()[0][0],
        ];
    }
}
