<?php

namespace Fjord\Chart\Loader;

use Closure;
use Fjord\Chart\ChartSet;
use Carbon\CarbonInterface;
use Illuminate\Support\Arr;

class NumberLoader extends ChartLoader
{
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

        $nextTimeResolver = $this->getNextTimeResolver();

        $query = $this->config->model::query();

        $set = ChartSet::make($query, $valueResolver, $timeResolver)
            ->label($labelResolver)
            ->iterations(1);

        $set->load($startTime);

        if ($this->config->compare) {
            $set->load($nextTimeResolver($startTime));
        }

        return [
            'results' => Arr::flatten($set->getValues()),
            'value' => $set->getValues()[0][0]
        ];
    }

    protected function getQueryResolverConfig()
    {
        return [
            'today' => fn ($query, $column, $time) => $query->whereInDay($column, $time),
            'yesterday' => fn ($query, $column, $time) => $query->whereInDay($column, $time),
            'last7days' => fn ($query, $column, $time) => $query->whereInDays($column, $time, 7),
            'thisweek' => fn ($query, $column, $time) => $query->whereInWeek($column, $time),
            'last30days' => fn ($query, $column, $time) => $query->whereInDays($column, $time, 30),
            'thismonth' => fn ($query, $column, $time) => $query->whereInMonth($column, $time)
        ];
    }

    protected function getNextTimeResolverConfig()
    {
        return [
            'today' => fn ($time) => $time->subDay(),
            'yesterday' => fn ($time) => $time->subWeek(),
            'last7days' => fn ($time) => $time->subWeek(),
            'thisweek' => fn ($time) => $time->subWeek(),
            'last30days' => fn ($time) => $time->subDays(30),
            'thismonth' => fn ($time) => $time->subMonth(),
        ];
    }
}
