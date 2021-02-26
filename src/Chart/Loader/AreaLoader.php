<?php

namespace Ignite\Chart\Loader;

use Carbon\CarbonInterface;
use Closure;
use Ignite\Chart\ChartSet;

class AreaLoader extends ChartLoader
{
    use Concerns\HasGoal;
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
            ->iterations($iterations);

        $set->load($startTime);

        if ($this->config->compare) {
            $set->load($nextTimeResolver($startTime));
        }

        return [
            'results' => $this->getResults($set),
            'chart'   => $this->engine->render(
                $this->getNames(),
                $set,
            ),
        ];
    }

    public function getResults(ChartSet $set)
    {
        return collect($set->getValues())->map(function ($values) {
            return $this->config->result($values);
        });
    }

    protected function getNextTimeResolverConfig()
    {
        return [
            'last24hours' => fn ($time) => $time->subHours(23),
            'today'       => fn ($time) => $time->subDay(),
            'yesterday'   => fn ($time) => $time->subWeek(),
            'last7days'   => fn ($time) => $time->subDays(6),
            'thisweek'    => fn ($time) => $time->subWeek(),
            'last30days'  => fn ($time) => $time->subDays(29),
            'thismonth'   => fn ($time) => $time->subMonth(),
            'thisyear'    => fn ($time) => $time->subYear(),
        ];
    }
}
