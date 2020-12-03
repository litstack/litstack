<?php

namespace Ignite\Crud\Models\Concerns;

use Ignite\Crud\Models\Media;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

trait HasMedia
{
    use InteractsWithMedia;

    /**
     * Media relation.
     *
     * @return morphMany
     */
    public function media(): MorphMany
    {
        // Using Lit media model.
        return $this->morphMany(Media::class, 'model');
    }

    /**
     * Register media conversions for field.
     *
     * @param  SpatieMedia $media
     * @return void
     */
    public function registerMediaConversions(SpatieMedia $media = null): void
    {
        $this->applyCrop($this->addMediaConversion('preview'), $media);

        foreach (config('lit.mediaconversions.default') as $key => $value) {
            $conversion = $this->addMediaConversion($key)
                ->keepOriginalImageFormat()
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
            $crop['width'],
            $crop['height'],
            $crop['x'],
            $crop['y']
        );
    }
}
