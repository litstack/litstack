<?php

namespace Lit\Crud\Models\Traits;

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
}
