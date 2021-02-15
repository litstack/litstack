<?php

namespace Ignite\Chart;

use Carbon\CarbonInterface;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ChartSet
{
    /**
     * Query builder.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Iterations.
     *
     * @var int
     */
    protected $iterations;

    /**
     * Labels.
     *
     * @var array
     */
    protected $labels = [];

    /**
     * Values.
     *
     * @var array
     */
    protected $values = [];

    /**
     * Label resolver closure.
     *
     * @var Closure
     */
    protected $labelResolver;

    /**
     * Value resolver closure.
     *
     * @var Closure
     */
    protected $valueResolver;

    /**
     * Time resolver closure.
     *
     * @var Closure
     */
    protected $timeResolver;

    /**
     * Create new ChartSet instance.
     *
     * @param Builder $query
     * @param Closure $valueResolver
     * @param Closure $timeResolver
     */
    public function __construct($query, Closure $valueResolver, Closure $timeResolver)
    {
        $this->query = $query;
        $this->valueResolver = $valueResolver;
        $this->timeResolver = $timeResolver;
    }

    /**
     * Make chart set.
     *
     * @param Builder $query
     * @param Closure $valueClosure
     * @param Closure $timeResolver
     *
     * @return void
     */
    public static function make($query, Closure $valueResolver, Closure $timeResolver)
    {
        return new self($query, $valueResolver, $timeResolver);
    }

    /**
     * Set label resolver.
     *
     * @param  Closure $closure
     * @return $this
     */
    public function label(Closure $closure)
    {
        $this->labelResolver = $closure;

        return $this;
    }

    /**
     * Set start time.
     *
     * @param  CarbonInterface $time
     * @return $this
     */
    public function time(CarbonInterface $time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Set iterations.
     *
     * @param  int   $iterations
     * @return $this
     */
    public function iterations(int $iterations)
    {
        $this->iterations = $iterations;

        return $this;
    }

    /**
     * Reset.
     *
     * @return $this
     */
    public function reset()
    {
        $this->labels = [];

        return $this;
    }

    /**
     * Load values.
     *
     * @return void
     */
    public function load(CarbonInterface $time)
    {
        $this->time = $time;
        $this->time->setLocale(lit()->getLocale());
        $this->reset();

        $statements = [];
        for ($i = 0; $i < $this->iterations; $i++) {
            $time = $this->getTimeFromIterationKey($i);

            $statement = $this->getSelectFromTime($time) ?? 0;

            if (is_array($statement)) {
                $statements = array_merge($statement, $statements);
            } else {
                $statements[] = $statement;
            }

            $this->labels[] = $this->getLabelFromTime($time);
        }

        $this->values[] = $this->convertNullValues(
            $this->getValuesFromStatements($statements)
        );

        return $this;
    }

    /**
     * Get values from selects.
     *
     * @param  array      $selects
     * @return Collection
     */
    protected function getValuesFromStatements(array $selects)
    {
        return $this->getQueryFromStatements($selects)
            ->get('value')
            ->pluck('value');
    }

    /**
     * Modify query with select statement.
     *
     * @param  array   $select
     * @return Builder
     */
    protected function getQueryFromStatements(array $selects)
    {
        $query = array_shift($selects);

        foreach ($selects as $select) {
            $query->unionAll($select);
        }

        return $query;
    }

    /**
     * Convert null values to integer.
     *
     * @param  Collection $values
     * @return Collection
     */
    protected function convertNullValues(Collection $values)
    {
        return $values->map(
            fn ($value) => (int) $value
        );
    }

    /**
     * Get default label.
     *
     * @param \Carbon\CarbonInterface $time
     *
     * @return string
     */
    protected function getDefaultLabel(CarbonInterface $time)
    {
        return $time->format('d.m.y');
    }

    /**
     * Get label from time.
     *
     * @param  \Carbon\CarbonInterface $time
     * @return string
     */
    public function getLabelFromTime(CarbonInterface $time)
    {
        $labelResolver = $this->labelResolver
            ?: fn ($time) => $this->getDefaultLabel($time);

        return $labelResolver($time);
    }

    /**
     * Get select query from time.
     *
     * @param  CarbonInterface $time
     * @return Builder
     */
    public function getSelectFromTime(CarbonInterface $time)
    {
        $valueResolver = $this->valueResolver;

        return $valueResolver(clone $this->query, $time);
    }

    /**
     * Get values.
     *
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Get labels.
     *
     * @return array
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * Get time from iteration key.
     *
     * @param int $i
     *
     * @return \Carbon\CarbonInterface
     */
    protected function getTimeFromIterationKey(int $i): CarbonInterface
    {
        $timeResolver = $this->timeResolver;

        return $timeResolver($this->time->copy(), $i);
    }
}
