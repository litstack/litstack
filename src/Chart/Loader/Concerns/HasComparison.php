<?php

namespace Fjord\Chart\Loader\Concerns;

trait HasComparison
{
    /**
     * Get next time resolver config.
     *
     * @return array
     */
    protected function getNextTimeResolverConfig()
    {
        return [
            'today'      => fn ($time)      => $time->subDay(),
            'yesterday'  => fn ($time)  => $time->subWeek(),
            'last7days'  => fn ($time)  => $time->subWeek(),
            'thisweek'   => fn ($time)   => $time->subWeek(),
            'last30days' => fn ($time) => $time->subDays(30),
            'thismonth'  => fn ($time)  => $time->subMonth(),
        ];
    }
}
