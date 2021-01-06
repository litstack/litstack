<?php

namespace Ignite\Page\Table\Casts;

use Carbon\Carbon;
use Ignite\Page\Table\ColumnCast;

class CarbonColumn extends ColumnCast
{
    /**
     * Format string.
     *
     * @var string
     */
    protected $format;

    /**
     * Create new MoneyCast instance.
     *
     * @param  string      $currency
     * @param  string|null $locale
     * @return void
     */
    public function __construct($format = 'd.m.Y')
    {
        $this->format = $format;
    }

    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string                              $key
     * @param  mixed                               $value
     * @param  array                               $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        if (is_null($value)) {
            return;
        }

        return (new Carbon($value))->format($this->format);
    }
}
