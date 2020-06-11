<?php

namespace Fjord\Crud\Controllers\Api\Relations;

use Fjord\Crud\Fields\Relations\HasOne;
use Fjord\Crud\Requests\CrudUpdateRequest;

trait ManagesHasOne
{
    /**
     * Create hasOne relation.
     *
     * @param CrudUpdateRequest $request
     * @param HasOne $field
     * @param mixed $model
     * @param mixed $relation
     * @return void
     */
    protected function createHasOne(CrudUpdateRequest $request, HasOne $field, $model, $relation)
    {
        $hasOne = $field->getRelationQuery($model);

        $query = [
            $hasOne->getForeignKeyName() => $model->{$hasOne->getLocalKeyName()}
        ];

        // Remove existing hasOne relations.
        $hasOne->where($query)->update([
            $hasOne->getForeignKeyName() => null
        ]);

        // Create new relation.
        $relation->update($query);
    }

    /**
     * Remove hasOne relation.
     *
     * @param CrudUpdateRequest $request
     * @param HasOne $field
     * @param mixed $model
     * @param mixed $relation
     * @return void
     */
    protected function destroyHasOne(CrudUpdateRequest $request, HasOne $field, $model, $relation)
    {
        $hasOne = $field->getRelationQuery($model);

        $relation->update([
            $hasOne->getForeignKeyName() => null
        ]);
    }
}
