<?php

namespace Ignite\Chart\Loader\Concerns;

trait HasOneIteration
{
    /**
     * Get query resolver config.
     *
     * @return array
     */
    protected function getQueryResolverConfig()
    {
        return [
            'last24hours' => fn ($query, $column, $time) => $query->whereInDay($column, $time),
            'today'       => fn ($query, $column, $time) => $query->whereInDay($column, $time),
            'yesterday'   => fn ($query, $column, $time) => $query->whereInDay($column, $time),
            'last7days'   => fn ($query, $column, $time) => $query->whereInDays($column, $time, 7),
            'thisweek'    => fn ($query, $column, $time) => $query->whereInWeek($column, $time),
            'last30days'  => fn ($query, $column, $time) => $query->whereInDays($column, $time, 30),
            'thismonth'   => fn ($query, $column, $time) => $query->whereInMonth($column, $time),
            'thisyear'    => fn ($query, $column, $time) => $query->whereInYear($column, $time),
        ];
    }
}
