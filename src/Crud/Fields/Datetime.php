<?php

namespace Fjord\Crud\Fields;

use Carbon\CarbonInterface;
use Fjord\Crud\BaseField;
use Illuminate\Support\Carbon;

class Datetime extends BaseField
{
    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-date-time';

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = [];

    /**
     * Set default attributes
     *
     * @return void
     */
    public function setDefaultAttributes()
    {
        $this->formatted('llll');
        $this->inline(false);
        $this->onlyDate(true);
    }

    /**
     * Set formatted.
     *
     * @param string $format
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
     * @param boolean $inline
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
     * @param boolean $date
     * @return $this
     */
    public function onlyDate(bool $date = true)
    {
        $this->setAttribute('only_date', $date);

        return $this;
    }

    /**
     * Cast field value.
     *
     * @param mixed $value
     * @return boolean
     */
    public function cast($value)
    {
        if ($value instanceof CarbonInterface) {
            return $value;
        }

        return new Carbon($value);
    }
}
