<?php

namespace Ignite\Crud\Fields;

use Carbon\CarbonInterface;
use Ignite\Crud\BaseField;
use Ignite\Support\Facades\Lit;
use Illuminate\Support\Carbon;

class Datetime extends BaseField
{
    use Traits\FieldHasRules;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'lit-field-date-time';

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = [];

    /**
     * Set default attributes.
     *
     * @return void
     */
    public function mount()
    {
        $this->includeCtkScript();

        $this->formatted('l');
        $this->inline(false);
        $this->onlyDate(true);
        $this->minuteInterval(5);
    }

    /**
     * Inlcude ctk datetime picker script.
     *
     * @see https://github.com/chronotruck/vue-ctk-date-time-picker
     * @return void
     */
    protected function includeCtkScript()
    {
        Lit::script(lit()->route('ctk.js'));
    }

    /**
     * Set formatted.
     *
     * @param  string $format
     * @return $this
     */
    public function formatted(string $format)
    {
        $this->setAttribute('formatted', $format);

        return $this;
    }

    /**
     * Set inline.
     *
     * @param  bool  $inline
     * @return $this
     */
    public function inline(bool $inline = true)
    {
        $this->setAttribute('inline', $inline);

        return $this;
    }

    /**
     * Set only date.
     *
     * @param  bool  $date
     * @return $this
     */
    public function onlyDate(bool $dateOnly = true)
    {
        $this->setAttribute('only_date', $dateOnly);

        if (! $dateOnly) {
            $this->formatted('llll');
        } else {
            $this->formatted('l');
        }

        return $this;
    }

    /**
     * Set only time.
     *
     * @param  bool  $date
     * @return $this
     */
    public function onlyTime(bool $timeOnly = true)
    {
        $this->setAttribute('only_time', $timeOnly);

        $this->onlyDate(false);

        if ($timeOnly) {
            $this->formatted('LT');
        }

        return $this;
    }

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
     * Align datepicker on right.
     *
     * @param  bool  $right
     * @return $this
     */
    public function right(bool $right = true)
    {
        $this->setAttribute('right', $right);

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
     * Set disabled hours option.
     *
     * @param  int   $disabledHours
     * @return $this
     */
    public function disabledHours(array $disabledHours = [])
    {
        $this->setAttribute('disabled_hours', $disabledHours);

        return $this;
    }
}
