<?php

namespace Ignite\Crud\Models\Concerns;

use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasMedia
{
    /**
     * Register media conversions for field.
     *
     * @param  Media $media
     * @return void
     */
    public function registerCrudMediaConversions(Media $media = null)
    {
        $this->applyCrop($this->addMediaConversion('preview'), $media);

        foreach (config('lit.mediaconversions.default') as $key => $value) {
            $conversion = $this->addMediaConversion($key)
                ->width($value[0])
                ->height($value[1])
                ->sharpen($value[2]);

            $this->applyCrop($conversion, $media);
        }
    }

    /**
     * Apply crop to the given conversion.
     *
     * @param  Conversion $conversion
     * @param  Media      $media
     * @return void
     */
    protected function applyCrop(Conversion $conversion, Media $media = null)
    {
        if (! $media) {
            return;
        }

        if (! array_key_exists('crop', $media->custom_properties)) {
            return;
        }

        $crop = $media->custom_properties['crop'];

        $conversion->manualCrop(
            $crop['width'], $crop['height'], $crop['x'], $crop['y']
        );
    }
}
