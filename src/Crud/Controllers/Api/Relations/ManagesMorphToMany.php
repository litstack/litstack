<?php

namespace Fjord\Crud\Controllers\Api\Relations;

use Illuminate\Support\Facades\DB;
use Fjord\Crud\Requests\CrudUpdateRequest;
use Fjord\Crud\Fields\Relations\MorphToMany;

trait ManagesMorphToMany
{
    /**
     * Create morphToMany relation.
     *
     * @param CrudUpdateRequest $request
     * @param MorphToMany $field
     * @param mixed $model
     * @param mixed $relation
     * @return mixed
     */
    public function createMorphToMany(CrudUpdateRequest $request, MorphToMany $field, $model, $relation)
    {
        $morphToMany = $field->relation($model, $query = true);

        DB::table($morphToMany->getTable())->insert([
            $morphToMany->getRelatedPivotKeyName() => $relation->{$morphToMany->getRelatedKeyName()},
            $morphToMany->getForeignPivotKeyName() => $model->{$morphToMany->getParentKeyName()},
            $morphToMany->getMorphType() => get_class($model)
        ]);
    }

    /**
     * Remove morphToMany relation.
     *
     * @param CrudUpdateRequest $request
     * @param MorphToMany $field
     * @param mixed $model
     * @param mixed $relation
     * @return void
     */
    protected function destroyMorphToMany(CrudUpdateRequest $request, MorphToMany $field, $model, $relation)
    {
        $morphToMany = $field->relation($model, $query = true);

        return DB::table($morphToMany->getTable())->where([
            $morphToMany->getRelatedPivotKeyName() => $relation->{$morphToMany->getRelatedKeyName()},
            $morphToMany->getForeignPivotKeyName() => $model->{$morphToMany->getParentKeyName()},
            $morphToMany->getMorphType() => get_class($model)
        ])->delete();
    }
}
