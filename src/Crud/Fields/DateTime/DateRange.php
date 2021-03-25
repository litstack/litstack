<?php

namespace Ignite\Crud\Fields\DateTime;

use Ignite\Contracts\Crud\Fields\ModifiesMultipleAttributes;
use Ignite\Crud\Field;

class DateRange extends Field implements ModifiesMultipleAttributes
{
    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'lit-field-date-range';

    /**
     * Required field attributes.
     *
     * @var array
     */
    public $required = [];

    /**
     * Create new Field instance.
     *
     * @param  string $startAttribute
     * @param  string $endAttribute
     * @return void
     */
    public function __construct(string $startAttribute, string $endAttribute)
    {
        parent::__construct("{$startAttribute}_{$endAttribute}");

        $this->setAttribute('local_keys', [$startAttribute, $endAttribute]);
        $this->setAttribute('local_key', null);
        $this->setAttribute('attributes', [
            'start' => $startAttribute,
            'end'   => $endAttribute,
        ]);
    }

    public function getModifiedAttributes()
    {
        return $this->local_keys;
    }

    public function withTime(bool $time = true)
    {
        $this->setAttribute('mode', 'dateTime');

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
     * Set default attributes.
     *
     * @return void
     */
    public function mount()
    {
        $this->setAttribute('mode', 'date');
        // $this->mask('YYYY-MM-DD');
        // $this->mode('date');
        $this->is24hr(true);
        // $this->minuteInterval(1);
    }
}
