<?php

namespace Fjord\Chart\Loader;

use Closure;
use Fjord\Chart\ChartSet;
use Carbon\CarbonInterface;

class AreaLoader extends ChartLoader
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
            ->iterations($iterations);

        $set->load($startTime);

        if ($this->config->compare) {
            $set->load($nextTimeResolver($startTime));
        }

        return [
            'results' => $this->getResults($set),
            'chart' => $this->engine->render(
                'area',
                $this->getNames(),
                $set,
                $this->getGoal()
            )
        ];
    }

    public function getGoal()
    {
        if ($this->config->goal === null) {
            return;
        }

        if ($this->getShowGoal()) {
            return $this->config->goal;
        }
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
            'today' => fn ($time) => $time->subDay(),
            'yesterday' => fn ($time) => $time->subWeek(),
            'last7days' => fn ($time) => $time->subWeek(),
            'thisweek' => fn ($time) => $time->subWeek(),
            'last30days' => fn ($time) => $time->subDays(30),
            'thismonth' => fn ($time) => $time->subMonth()
        ];
    }

    protected function getShowGoalConfig()
    {
        return [
            'today' => false,
            'yesterday' => false,
            'last7days' => true,
            'thisweek' => true,
            'last30days' => true,
            'thismonth' => true
        ];
    }
}
