<?php

namespace Fjord\Crud;

use Fjord\Crud\Models\FormBlock;
use Fjord\Crud\Models\FormField;

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
            return parent::getRelation($model);
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
        if ($this->maxFiles == 1) {
            return $media->first();
        }

        return $media;
    }
}
