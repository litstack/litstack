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

        $query = [
            $morphToMany->getRelatedPivotKeyName() => $relation->{$morphToMany->getRelatedKeyName()},
            $morphToMany->getForeignPivotKeyName() => $model->{$morphToMany->getParentKeyName()},
            $morphToMany->getMorphType() => $morphToMany->getMorphClass()
        ];

        // Sortable
        if ($field->sortable) {
            $query[$field->orderColumn] = $morphToMany->count();
        }

        DB::table($morphToMany->getTable())->insert($query);
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
            $morphToMany->getMorphType() => $morphToMany->getMorphClass()
        ])->delete();
    }
}
