<?php

namespace Ignite\Chart\Loader;

use BadMethodCallException;
use Carbon\CarbonInterface;
use Closure;
use Exception;
use Ignite\Chart\ChartRequest;
use Ignite\Chart\Contracts\Engine;
use Ignite\Config\ConfigHandler;
use Illuminate\Support\Str;

abstract class ChartLoader
{
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
    abstract protected function makeSeries(
        CarbonInterface $startTime,
        int $iterations,
        Closure $timeResolver,
        Closure $valueResolver,
        Closure $labelResolver
    ): array;

    /**
     * Create new DonutLoader instance.
     *
     * @param \Ignite\Config\ConfigHandler   $config
     * @param \Ignite\Chart\Contracts\Engine $engine
     */
    public function __construct(ConfigHandler $config, Engine $engine)
    {
        $this->config = $config;
        $this->engine = $engine;
    }

    public function get(ChartRequest $request)
    {
        $this->setTimespanType(
            $request->type ?: abort(404)
        );

        $startTime = $this->getStartTime();
        $timeResolver = $this->getStartTime();
        $iterations = $this->getIterations();
        $timeResolver = $this->getTimeResolver();
        $valueResolver = $this->getValueResolver();
        $labelResolver = $this->getLabelResolver();

        return $this->makeSeries(
            $startTime,
            $iterations,
            $timeResolver,
            $valueResolver,
            $labelResolver
        );
    }

    public function setTimespanType(string $type)
    {
        $this->timespanType = $type;
    }

    public function getTimespanType()
    {
        return $this->timespanType;
    }

    protected function getConfigKeyFor(string $method)
    {
        if (! $timespanType = $this->getTimespanType()) {
            throw new Exception('Missing timespan type.');
        }

        return $this->{"{$method}Config"}()[$timespanType];
    }

    protected function getValueResolver()
    {
        $queryResolver = $this->getQueryResolver();

        return function ($query, $time) use ($queryResolver) {
            $query = $queryResolver($query, $this->config->created_at, $time);

            return $this->config->value($query);
        };
    }

    protected function getStartTimeConfig()
    {
        return [
            'today'      => now()->startOfDay(),
            'yesterday'  => now()->startOfDay()->subDay(),
            'last7days'  => now()->subDays(7),
            'thisweek'   => now()->startOfWeek(),
            'last30days' => now()->subDays(30),
            'thismonth'  => now()->startOfMonth(),
        ];
    }

    protected function getIterationsConfig()
    {
        return [
            'today'      => 24,
            'yesterday'  => 24,
            'last7days'  => 7,
            'thisweek'   => 7,
            'last30days' => 30,
            'thismonth'  => 31,
        ];
    }

    protected function getTimeResolverConfig()
    {
        return [
            'today'      => fn ($time, $i) => $time->addHours($i),
            'yesterday'  => fn ($time, $i) => $time->addHours($i),
            'last7days'  => fn ($time, $i) => $time->addDays($i),
            'thisweek'   => fn ($time, $i) => $time->addDays($i),
            'last30days' => fn ($time, $i) => $time->addDays($i),
            'thismonth'  => fn ($time, $i) => $time->addDays($i),
        ];
    }

    protected function getQueryResolverConfig()
    {
        return [
            'today'      => fn ($query, $column, $time) => $query->whereInHour($column, $time),
            'yesterday'  => fn ($query, $column, $time) => $query->whereInHour($column, $time),
            'last7days'  => fn ($query, $column, $time) => $query->whereInDay($column, $time),
            'thisweek'   => fn ($query, $column, $time) => $query->whereInDay($column, $time),
            'last30days' => fn ($query, $column, $time) => $query->whereInDay($column, $time),
            'thismonth'  => fn ($query, $column, $time) => $query->whereInDay($column, $time),
        ];
    }

    protected function getLabelResolverConfig()
    {
        return [
            'today'      => fn ($time) => $time->hour,
            'yesterday'  => fn ($time) => $time->hour,
            'last7days'  => fn ($time) => $time->format('l'),
            'thisweek'   => fn ($time) => $time->format('l'),
            'last30days' => fn ($time) => $time->format('l'),
            'thismonth'  => fn ($time) => $time->format('l'),
        ];
    }

    protected function getNamesConfig()
    {
        return [
            'today'      => ['Today', 'Yesterday'],
            'yesterday'  => ['Yesterday', 'Day before Yesterday'],
            'last7days'  => ['Last 7 Days', 'Previous 7 Days'],
            'thisweek'   => ['This Week', 'Last Week'],
            'last30days' => ['Last 30 Days', 'Previouse 30 Days'],
            'thismonth'  => ['This Month', 'Last Month'],
        ];
    }

    public function __call($method, $parameters = [])
    {
        if (Str::startsWith($method, 'get') && ! Str::endsWith($method, 'Config')) {
            return $this->getConfigKeyFor($method, ...$parameters);
        }

        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.',
            static::class,
            $method
        ));
    }
}
