<?php

namespace Fjord\Chart;

use Closure;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ChartSet
{
    protected $query;

    protected $iterations;

    protected $labels = [];

    protected $values = [];

    protected $labelClosure;

    protected $valueClosure;

    protected $timeClosure;

    public function __construct(
        Builder $query,
        Closure $valueClosure,
        Closure $timeClosure
    ) {
        $this->query = $query;
        $this->valueClosure = $valueClosure;
        $this->timeClosure = $timeClosure;
    }

    public static function make(
        Builder $query,
        Closure $valueClosure,
        Closure $timeClosure
    ) {
        return new self(
            $query,
            $valueClosure,
            $timeClosure
        );
    }

    public function label(Closure $closure)
    {
        $this->labelClosure = $closure;

        return $this;
    }

    /**
     * Set time.
     *
     * @param CarbonInterface $time
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
     * @param integer $iterations
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
     * Load.
     *
     * @return void
     */
    public function load(CarbonInterface $time)
    {
        $this->time = $time;
        $this->reset();

        $values = [];
        for ($i = 0; $i < $this->iterations; $i++) {
            $time = $this->getTimeFromIterationKey($i);

            $values[] = $this->getValueFromTime($time);
            $this->labels[] = $this->getLabelFromTime($time);
        }
        $this->values[] = $values;

        return $this;
    }

    protected function getDefaultLabel($time)
    {
        return $time->format("d.m.y");
    }

    public function getLabelFromTime(CarbonInterface $time)
    {
        $labelClosure = $this->labelClosure ?: fn ($time) => $this->getDefaultLabel($time);

        return $labelClosure($time);
    }

    public function getValueFromTime(CarbonInterface $time)
    {
        $valueClosure = $this->valueClosure;

        return $valueClosure(clone $this->query, $time);
    }

    public function getValues()
    {
        return $this->values;
    }

    public function getLabels()
    {
        return $this->labels;
    }

    protected function getTimeFromIterationKey($i)
    {
        $timeClosure = $this->timeClosure;
        return $timeClosure($this->time->copy(), $i);
    }
}
