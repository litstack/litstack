<?php

namespace Fjord\Crud\Models\Concerns;

use Spatie\MediaLibrary\Models\Media;

trait HasMedia
{
    /**
     * Register media conversions for field.
     *
     * @param Media $media
     * @return void
     */
    public function registerCrudMediaConversions(Media $media = null)
    {
        foreach (config('fjord.mediaconversions.default') as $key => $value) {
            $this->addMediaConversion($key)
                ->width($value[0])
                ->height($value[1])
                ->sharpen($value[2]);
        }
    }
}
