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
        $belongsToMany = $field->relation($model, $query = true);

        DB::table($belongsToMany->getTable())->insert([
            $belongsToMany->getForeignPivotKeyName() => $model->{$belongsToMany->getParentKeyName()},
            $belongsToMany->getRelatedPivotKeyName() => $relation->{$belongsToMany->getRelatedKeyName()}
        ]);
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
        $belongsToMany = $field->relation($model, $query = true);
        return DB::table($belongsToMany->getTable())->where([
            $belongsToMany->getForeignPivotKeyName() => $model->{$belongsToMany->getParentKeyName()},
            $belongsToMany->getRelatedPivotKeyName() => $relation->{$belongsToMany->getRelatedKeyName()}
        ])->delete();
    }
}
