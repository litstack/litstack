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
        $hasMany = $field->relation($model, $query = true);

        // Create new relation.
        $relation->update([
            $hasMany->getForeignKeyName() => $model->{$hasMany->getLocalKeyName()}
        ]);
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
        $hasOne = $field->relation($model, $query = true);

        $relation->update([
            $hasOne->getForeignKeyName() => null
        ]);
    }
}
