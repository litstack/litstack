<?php

namespace Fjord\Crud;

abstract class RelationField extends Field
{
    /**
     * Get relation query for model.
     *
     * @param mixed $model
     * @return mixed
     */
    abstract public function getRelationQuery($model);

    /**
     * Get relation from model.
     *
     * @param mixed $model
     * @return mixed
     */
    public function relation($model)
    {
        // TODO: Merge getRelation and this method since one of them is reduntant.
        return $this->getRelationQuery($model);
    }

    /**
     * Get results for model.
     *
     * @param mixed $model
     * @return mixed
     */
    public function getResults($model)
    {
        return $this->relation($model)->getResults();
    }
}
