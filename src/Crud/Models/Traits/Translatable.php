<?php

namespace Ignite\Crud\Models\Traits;

use Astrotomic\Translatable\Translatable as AstronomicTranslatable;

trait Translatable
{
    use AstronomicTranslatable;

    /**
     * Append the translation to each query.
     */
    public function getTranslationAttribute()
    {
        return $this->getTranslationsArray();
    }

    /**
     * Retrieve the model for a translated, bound value.
     *
     * @param  mixed                                    $value
     * @param  string|null                              $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?? $this->getRouteKeyName();

        if (in_array($field, $this->translatedAttributes)) {
            return $this->whereTranslation($field, $value, app()->getLocale())->first();
        }

        return $this->where($field, $value)->first();
    }
}
