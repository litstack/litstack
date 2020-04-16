<?php

namespace Fjord\Crud\Concerns;

trait ManagesRelationField
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
        return $model->{$this->id};
    }

    /**
     * Is field relation.
     *
     * @return boolean
     */
    public function isRelation()
    {
        return true;
    }

    /**
     * Get relation from model.
     *
     * @param mixed $model
     * @param bool $query
     * @return mixed
     */
    public function relation($model, bool $query = false)
    {
        $builder = $this->getRelation($model);

        if ($query) {
            return $builder;
        }

        return $this->resolveQuery($builder);
    }
}
