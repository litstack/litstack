<?php

namespace Ignite\Crud\Fields\DateTime;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Ignite\Crud\BaseField;
use Ignite\Crud\Fields\Traits\FieldHasRules;

class BaseDateTime extends BaseField
{
    use FieldHasRules;

    /**
     * Cast field value.
     *
     * @param  mixed $value
     * @return bool
     */
    public function castValue($value)
    {
        if ($value instanceof CarbonInterface) {
            return $value;
        }

        return new Carbon($value);
    }

    /**
     * Set mask.
     *
     * @param  string $mask
     * @return $this
     */
    public function mask(string $mask = 'YYYY-MM-DD HH:mm:ss')
    {
        $this->setAttribute('mask', $mask);

        return $this;
    }

    /**
     * Set timepicker minute interval.
     *
     * @param  int   $interval
     * @return $this
     */
    public function minuteInterval(int $interval = 1)
    {
        $this->setAttribute('minute_interval', $interval);

        return $this;
    }

    /**
     * Set timepicker mode.
     *
     * @param  int   $mode
     * @return $this
     */
    public function mode(string $mode)
    {
        $this->setAttribute('mode', $mode);

        return $this;
    }

    /**
     * Set timepicker is24hr.
     *
     * @param  int   $is24hr
     * @return $this
     */
    public function is24hr(bool $is24hr = true)
    {
        $this->setAttribute('is24hr', $is24hr);

        return $this;
    }

    /**
     * Set timepicker inline.
     *
     * @param  int   $inline
     * @return $this
     */
    public function inline(bool $inline = true)
    {
        $this->setAttribute('inline', $inline);

        return $this;
    }

    /**
     * Expand timepicker.
     *
     * @param  int   $expand
     * @return $this
     */
    public function expand(bool $expand = true)
    {
        $this->setAttribute('expand', $expand);

        return $this;
    }

    /**
     * Trim weeks.
     *
     * @param  int   $trimWeeks
     * @return $this
     */
    public function trimWeeks(bool $trimWeeks = true)
    {
        $this->setAttribute('trimWeeks', $trimWeeks);

        return $this;
    }

    /**
     * Set rows.
     *
     * @param  int   $rows
     * @return $this
     */
    public function rows(int $rows = 1)
    {
        $this->setAttribute('rows', $rows);

        return $this;
    }

    /**
     * Set min date.
     *
     * @param  int   $minDate
     * @return $this
     */
    public function minDate(Carbon $minDate)
    {
        $this->setAttribute('minDate', $minDate);

        return $this;
    }

    /**
     * Set max date.
     *
     * @param  int   $maxDate
     * @return $this
     */
    public function maxDate(Carbon $maxDate)
    {
        $this->setAttribute('maxDate', $maxDate);

        return $this;
    }

    /**
     * Set events.
     *
     * @param  int   $events
     * @return $this
     */
    public function events(array $events)
    {
        $this->setAttribute('events', $events);

        return $this;
    }

    /**
     * Deprecated Set only date.
     *
     * @deprecated
     * @param  bool $onlyDate
     * @return void
     */
    public function onlyDate(bool $onlyDate = true)
    {
        if ($onlyDate) {
            $this->setAttribute('mask', 'YYYY-MM-DD');
            $this->setAttribute('mode', 'date');
        } else {
            $this->setAttribute('mask', 'YYYY-MM-DD HH:mm:ss');
            $this->setAttribute('mode', 'datetime');
        }

        return $this;
    }

    /**
     * Deprecated Set only time.
     *
     * @deprecated
     * @param  bool $onlyDate
     * @return void
     */
    public function onlyTime(bool $onlyTime = true)
    {
        if ($onlyTime) {
            $this->setAttribute('mask', 'HH:mm:ss');
            $this->setAttribute('mode', 'time');
        } else {
            $this->setAttribute('mask', 'YYYY-MM-DD HH:mm:ss');
            $this->setAttribute('mode', 'datetime');
        }

        return $this;
    }

    /**
     * Deprecated Set formatted.
     *
     * @deprecated
     * @param  string $format
     * @return $this
     */
    public function formatted(string $format)
    {
        return $this;
    }
}
