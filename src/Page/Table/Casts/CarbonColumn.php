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
     * Wheter to use iso format.
     *
     * @var bool
     */
    protected $isoFormat;

    /**
     * Create new CarbonColumn instance.
     *
     * @param  string      $currency
     * @param  string|null $locale
     * @param  bool        $isoFormat
     * @return void
     */
    public function __construct($format = 'd.m.Y', $isoFormat = false)
    {
        $this->format = $format;
        $this->isoFormat = $isoFormat;
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

        $time = (new Carbon($value))
            ->setTimezone(config('app.timezone'));

        return $this->isoFormat
            ? $time->isoFormat($this->format)
            : $time->format($this->format);
    }
}
