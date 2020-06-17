<?php

namespace Fjord\Chart\Loader;

use Closure;
use Fjord\Chart\ChartSet;
use Carbon\CarbonInterface;

class DonutLoader extends ChartLoader
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

        $query = $this->config->model::query();

        $set = ChartSet::make($query, $valueResolver, $timeResolver)
            ->label($labelResolver)
            ->iterations(1);

        $set->load($startTime);

        return [
            'results' => [],
            'chart' => $this->engine->renderDonut(
                $this->config->labels,
                $set
            )
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
}
