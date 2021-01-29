<?php

namespace Ignite\Crud\Fields\DateTime;

use Ignite\Crud\Fields\Traits\FieldHasRules;

class Time extends BaseDateTime
{
    use FieldHasRules;

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
        $this->mask('HH:mm:ss');
        $this->mode('time');
        $this->is24hr(true);
        $this->minuteInterval(1);
    }
}
