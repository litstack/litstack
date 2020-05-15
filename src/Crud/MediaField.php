<?php

namespace Fjord\Crud;

use Fjord\Crud\Models\FormBlock;
use Fjord\Crud\Models\FormField;
use Illuminate\Database\Eloquent\Collection;

class MediaField extends ManyRelationField
{
    /**
     * Get relation for model.
     *
     * @param mixed $model
     * @param boolean $query
     * @return mixed
     */
    protected function getRelation($model)
    {
        if (!$model instanceof FormField && !$model instanceof FormBlock) {
            return $model->{$this->id};
        }

        return $model->getMedia($this->id);
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
