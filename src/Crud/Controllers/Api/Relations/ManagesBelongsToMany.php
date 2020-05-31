<?php

namespace Fjord\Crud\Controllers\Api\Relations;

use Illuminate\Support\Facades\DB;
use Fjord\Crud\Requests\CrudUpdateRequest;
use Fjord\Crud\Fields\Relations\BelongsToMany;

trait ManagesBelongsToMany
{
    /**
     * Add BelongsToMany relation.
     *
     * @param CrudUpdateRequest $request
     * @param BelongsToMany $field
     * @param mixed $model
     * @param mixed $relation
     * @return mixed
     */
    protected function createBelongsToMany(CrudUpdateRequest $request, BelongsToMany $field, $model, $relation)
    {
        $belongsToMany = $field->getRelationQuery($model);

        $query = [
            $belongsToMany->getForeignPivotKeyName() => $model->{$belongsToMany->getParentKeyName()},
            $belongsToMany->getRelatedPivotKeyName() => $relation->{$belongsToMany->getRelatedKeyName()}
        ];

        if ($field->sortable) {
            $query[$field->orderColumn] = $belongsToMany->count();
        }

        DB::table($belongsToMany->getTable())->insert($query);
    }

    /**
     * Remove BelongsToMany relation.
     *
     * @param CrudUpdateRequest $request
     * @param BelongsToMany $field
     * @param mixed $model
     * @param mixed $relation
     * @return void
     */
    protected function destroyBelongsToMany(CrudUpdateRequest $request, BelongsToMany $field, $model, $relation)
    {
        $belongsToMany = $field->getRelationQuery($model);
        return DB::table($belongsToMany->getTable())->where([
            $belongsToMany->getForeignPivotKeyName() => $model->{$belongsToMany->getParentKeyName()},
            $belongsToMany->getRelatedPivotKeyName() => $relation->{$belongsToMany->getRelatedKeyName()}
        ])->delete();
    }
}
