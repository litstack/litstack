<?php

namespace Ignite\Page\Table\Casts;

use Ignite\Page\Table\ColumnCast;
use Illuminate\Support\Carbon;

class CarbonColumn extends ColumnCast
{
    /**
     * Format string.
     *
     * @var string
     */
    protected $format;

    /**
     * Create new CarbonColumn instance.
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

        return (new Carbon($value))
            ->setTimezone(config('app.timezone'))
            ->format($this->format);
    }
}
