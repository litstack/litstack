<?php

namespace Fjord\Crud\Concerns;

use Closure;

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
        return $this->modifyQuery(
            $model->{$this->id}()
        );
    }

    /**
     * Get relation name.
     *
     * @return string
     */
    public function getRelationName()
    {
        return $this->id;
    }

    /**
     * Modify preview query with eager loads and accessors to append.
     *
     * @param Builder $query
     * @return Builder
     */
    protected function modifyQuery($query)
    {
        if (!$this->previewModifier instanceof Closure) {
            return $query;
        }

        $modifier = $this->previewModifier;
        $modifier($query);

        return $query;
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
