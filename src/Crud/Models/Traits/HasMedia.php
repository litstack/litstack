<?php

namespace Fjord\Crud\Models\Traits;

use Fjord\Crud\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

trait HasMedia
{
    use HasMediaTrait;

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
}
