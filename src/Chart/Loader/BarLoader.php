<?php

namespace Fjord\Chart\Loader;

use Closure;
use Fjord\Chart\ChartSet;
use Carbon\CarbonInterface;

class BarLoader extends ChartLoader
{
    use Concerns\HasGoal,
        Concerns\HasComparison;

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
                $this->getNames(),
                $set,
            )
        ];
    }

    public function getResults(ChartSet $set)
    {
        return collect($set->getValues())->map(function ($values) {
            return $this->config->result($values);
        });
    }
}
