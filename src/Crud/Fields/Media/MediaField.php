<?php

namespace Fjord\Crud;

use Fjord\Crud\RelationField;

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
     * @return mixed
     */
    public function getResults($model)
    {
        $results = $model->getMedia($this->id);

        if ($this->maxFiles == 1) {
            return $results->first();
        }

        return $results;
    }
}
