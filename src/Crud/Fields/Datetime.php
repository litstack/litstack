<?php

namespace Fjord\Crud\Fields;

use Carbon\CarbonInterface;
use Fjord\Crud\Field;
use Illuminate\Support\Carbon;

class Datetime extends Field
{
    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-date-time';

    /**
     * Required attributes.
     *
     * @var array
     */
    protected $required = [
        'title',
    ];

    /**
     * Available Field attributes.
     *
     * @var array
     */
    protected $available = [
        'title',
        'hint',
        'formatted',
        'inline',
        'only_date'
    ];

    /**
     * Default Field attributes.
     *
     * @var array
     */
    protected $defaults = [
        'formatted' => 'llll',
        'no_label' => false,
        'inline' => false,
        'only_date' => true
    ];

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
