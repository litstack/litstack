<?php

namespace Ignite\Chart\Loader;

use Carbon\CarbonInterface;
use Closure;
use Ignite\Chart\ChartSet;

class BarLoader extends ChartLoader
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

        $set->load($nextTimeResolver($startTime));

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
}
