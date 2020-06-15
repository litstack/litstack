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

        switch ($chart->getAttribute('type')) {
            case 'area':
                return $this->area($config, $engine, $type, $chart->getAttribute('type'));
                break;

            case 'donut':
                return $this->donut($config, $engine, $type, $chart->getAttribute('type'));
                break;
        }
    }

    protected function donut($config, $engine, $type, $chartType)
    {
        [
            $start, $iterations, $timeMethod, $queryMethod, $nextTime, $labelClosure, $names
        ] = $this->getType($type);

        if (!$start) {
            abort(404);
        }

        $queryMethod = $this->donutQueryMethod($type);

        $set = ChartSet::make(
            Sale::select('price'),
            fn ($query, $time) => $this->resolveValue($config, $queryMethod, $query, $time),
            fn ($time, $i) => $time->{$timeMethod}($i),
        )
            ->label($labelClosure)
            ->iterations(1);

        $set->load($start);

        return [
            'results' => collect($set->getValues())->map(
                fn ($values) => $config->result(collect($values))
            )->toArray(),
            'chart' => $engine->render($config->labels, $set, $chartType)
        ];
    }

    protected function area($config, $engine, $type, $chartType)
    {
        [
            $start, $iterations, $timeMethod, $queryMethod, $nextTime, $labelClosure, $names
        ] = $this->getType($type);

        if (!$start) {
            abort(404);
        }

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
            'chart' => $engine->render($names, $set, $chartType)
        ];
    }

    public function donutQueryMethod($type)
    {
        switch ($type) {
            case 'today':
                return fn ($query, $column, $time) => $query->whereInDay($column, $time);
                break;
            case 'yesterday':
                return fn ($query, $column, $time) => $query->whereInDay($column, $time);
                break;
            case 'last7days':
                return fn ($query, $column, $time) => $query->whereInDays($column, $time, 7);
                break;
            case 'thisweek':
                return fn ($query, $column, $time) => $query->whereInWeek($column, $time);
                break;
            case 'last30days':
                return fn ($query, $column, $time) => $query->whereInDays($column, $time, 30);
                break;
            case 'thismonth':
                return fn ($query, $column, $time) => $query->whereInMonth($column, $time);
                break;
        }
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
                    fn ($query, $column, $time) => $query->whereInHour($column, $time),
                    fn ($time) => $time->subDay(),
                    fn ($time) => $time->hour,
                    ['Today', 'Yesterday']
                ];
                break;
            case 'yesterday':
                return [
                    now()->startOfDay()->subDay(),
                    24,
                    'addHours',
                    fn ($query, $column, $time) => $query->whereInHour($column, $time),
                    fn ($time) => $time->subDay(),
                    fn ($time) => $time->hour,
                    ['Yesterday', 'Day before Yesterday']
                ];
                break;
            case 'last7days':
                return [
                    now()->subDays(7),
                    7,
                    'addDays',
                    fn ($query, $column, $time) => $query->whereInDay($column, $time),
                    fn ($time) => $time->subWeek(),
                    fn ($time) => $time->format('l'),
                    ['Last 7 Days', 'Previous 7 Days']
                ];
                break;
            case 'thisweek':
                return [
                    now()->startOfWeek(),
                    7,
                    'addDays',
                    fn ($query, $column, $time) => $query->whereInDay($column, $time),
                    fn ($time) => $time->subWeek(),
                    fn ($time) => $time->format('l'),
                    ['This Week', 'Last Week']
                ];
                break;
            case 'last30days':
                return [
                    now()->subDays(40),
                    30,
                    'addDays',
                    fn ($query, $column, $time) => $query->whereInDay($column, $time),
                    fn ($time) => $time->subDays(40),
                    fn ($time) => $time->day,
                    ['Last 30 Days', 'Previouse 30 Days']
                ];
                break;
            case 'thismonth':
                return [
                    now()->startOfMonth(),
                    31,
                    'addDays',
                    fn ($query, $column, $time) => $query->whereInDay($column, $time),
                    fn ($time) => $time->subMonth(),
                    fn ($time) => $time->day,
                    ['This Month', 'Last Month']
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
            $method($query, $config->created_at, $time)
        );
    }
}
