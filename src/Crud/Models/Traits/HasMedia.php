<?php

namespace Fjord\Crud\Models\Traits;

use Fjord\Crud\Models\Media;
use Spatie\MediaLibrary\Models\Media as SpatieMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

trait HasMedia
{
    use HasMediaTrait;

    /**
     * Media conversions group.
     *
     * @var string
     */
    public $mediaconversions = 'default';

    /**
     * Media relation.
     *
     * @return morphMany
     */
    public function media()
    {
        // Using Fjord media model.
        return $this->morphMany(Media::class, 'model');
    }

    /**
     * Register media conversions.
     *
     * @param SpatieMedia $media
     * @return void
     */
    public function registerMediaConversions(SpatieMedia $media = null)
    {
        foreach (config('fjord.mediaconversions.default') as $key => $value) {
            $this->addMediaConversion($key)
                ->width($value[0])
                ->height($value[1])
                ->sharpen($value[2]);
        }
    }
}
