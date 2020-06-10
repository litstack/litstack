<?php

namespace Fjord\Crud\Controllers\Api\Relations;

use Fjord\Crud\Fields\Relations\HasMany;
use Fjord\Crud\Requests\CrudUpdateRequest;

trait ManagesHasMany
{
    /**
     * Create hasMany relation.
     *
     * @param CrudUpdateRequest $request
     * @param HasMany $field
     * @param mixed $model
     * @param mixed $relation
     * @return void
     */
    protected function createHasMany(CrudUpdateRequest $request, HasMany $field, $model, $relation)
    {
        $hasMany = $field->getRelationQuery($model);

        $relation->{$hasMany->getForeignKeyName()} = $model->{$hasMany->getLocalKeyName()};

        // Sortable
        if ($field->sortable) {
            $relation->{$field->orderColumn} = $hasMany->count();
        }

        $relation->update();
    }

    /**
     * Remove hasMany relation.
     *
     * @param CrudUpdateRequest $request
     * @param HasMany $field
     * @param mixed $model
     * @param mixed $relation
     * @return void
     */
    protected function destroyHasMany(CrudUpdateRequest $request, HasMany $field, $model, $relation)
    {
        $hasOne = $field->getRelationQuery($model);

        $relation->update([
            $hasOne->getForeignKeyName() => null
        ]);
    }
}
