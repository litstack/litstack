<?php

namespace Fjord\Crud;

use Fjord\Crud\RelationField;
use Fjord\Crud\Models\FormBlock;
use Fjord\Crud\Models\FormField;
use Illuminate\Database\Eloquent\Collection;

class MediaField extends RelationField
{
    /**
     * Get relation query for model.
     *
     * @param mixed $model
     * @param boolean $query
     * @return mixed
     */
    public function getRelationQuery($model)
    {
        return $model->media()->where('collection', $this->id);
    }

    /**
     * Get results.
     *
     * @param mixed $model
     * @return void
     */
    public function getResults($model)
    {
        $results = $model->getMedia($this->id);

        if ($this->maxFiles == 1) {
            return $results->first();
        }

        return $results;
    }

    /**
     * Resolve query for media.
     *
     * @return \Illuminate\Database\Eloquent\Collection|Media
     */
    public function resolveQuery($media)
    {
        if ($this->maxFiles == 1 && $media instanceof Collection) {
            return $media->first();
        }

        return $media;
    }
}
