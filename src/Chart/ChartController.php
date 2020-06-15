<?php

namespace Fjord\Chart;

use Closure;
use App\Models\Sale;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Fjord\Support\Facades\Config;

class ChartController
{
    public $days = 7;

    public function get(ChartRequest $request)
    {
        $config = Config::get($request->key) ?: abort(404);
        $chart = app('fjord.chart.factory')->make($config);
        $engine = $chart->getEngine();
        $type = $request->type ?: abort(404);

        [
            $start, $iterations, $timeMethod, $queryMethod, $nextTime, $labelClosure
        ] = $this->getType($type);

        if (!$start) {
            abort(404);
        }

        $start = now()->subWeeks(2)->startOfWeek();

        $set = ChartSet::make(
            Sale::select('price'),
            fn ($query, $time) => $this->resolveValue($config, $queryMethod, $query, $time),
            fn ($time, $i) => $time->{$timeMethod}($i),
        )
            ->label($labelClosure)
            ->iterations($iterations);

        $set->load($start);
        $set->load($nextTime($start));

        return [
            'results' => collect($set->getValues())->map(
                fn ($values) => $config->result(collect($values))
            )->toArray(),
            'chart' => $engine->render(['This Week', 'Last Week'], $set)
        ];
    }

    /**
     * Get type.
     *
     * @param string $type
     * @return void
     */
    public function getType($type)
    {
        switch ($type) {
            case 'today':
                return [
                    now()->startOfDay(),
                    24,
                    'addHours',
                    'whereInHour',
                    fn ($time) => $time->subDay(),
                    fn ($time) => $time->hour,
                ];
                break;
            case 'yesterday':
                return [
                    now()->subDay()->startOfDay(),
                    24,
                    'addHours',
                    'whereInHour',
                    fn ($time) => $time->subDay(),
                    fn ($time) => $time->hour,
                ];
                break;
            case 'last7days':
                return [
                    now()->subDays(7),
                    7,
                    'addDays',
                    'whereInDay',
                    fn ($time) => $time->subWeek(),
                    fn ($time) => $time->format('l'),
                ];
                break;
            case 'thisweek':
                return [
                    now()->startOfWeek(),
                    7,
                    'addDays',
                    'whereInDay',
                    fn ($time) => $time->subWeek(),
                    fn ($time) => $time->format('l'),
                ];
                break;
            case 'last30days':
                return [
                    now()->subDays(40),
                    30,
                    'addDays',
                    'whereInDay',
                    fn ($time) => $time->subDays(40),
                    fn ($time) => $time->day,
                ];
                break;
            case 'thismonth':
                return [
                    now()->startOfMonth(),
                    31,
                    'addDays',
                    'whereInDay',
                    fn ($time) => $time->subMonth(),
                    fn ($time) => $time->day,
                ];
                break;
        }
    }

    /**
     * Resolve value.
     *
     * @param ConfigHandler $config
     * @param mixed $query
     * @param CarbonInterface $time
     * @return void
     */
    public function resolveValue($config, $method, $query, CarbonInterface $time)
    {
        return $config->value(
            $query->{$method}($config->created_at, $time)
        );
    }
}
