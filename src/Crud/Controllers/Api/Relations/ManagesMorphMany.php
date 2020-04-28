<?php

namespace Fjord\Crud\Controllers\Api\Relations;

use Fjord\Crud\Fields\Relations\MorphMany;
use Fjord\Crud\Requests\CrudUpdateRequest;

trait ManagesMorphMany
{
    /**
     * Create morphToMany relation.
     *
     * @param CrudUpdateRequest $request
     * @param MorphMany $field
     * @param mixed $model
     * @param mixed $relation
     * @return mixed
     */
    public function createMorphMany(CrudUpdateRequest $request, MorphMany $field, $model, $relation)
    {
        $morphMany = $field->relation($model, $query = true);

        $relation->update([
            $morphMany->getMorphType() => get_class($model),
            $morphMany->getForeignKeyName() => $model->{$morphMany->getLocalKeyName()}
        ]);
    }

    /**
     * Remove morphToMany relation.
     *
     * @param CrudUpdateRequest $request
     * @param MorphMany $field
     * @param mixed $model
     * @param mixed $relation
     * @return void
     */
    protected function destroyMorphMany(CrudUpdateRequest $request, MorphMany $field, $model, $relation)
    {
        $morphMany = $field->relation($model, $query = true);

        $relation->where([
            $morphMany->getMorphType() => get_class($model),
            $morphMany->getForeignKeyName() => $model->{$morphMany->getLocalKeyName()}
        ])->update([
            $morphMany->getMorphType() => '',
            $morphMany->getForeignKeyName() => null
        ]);
    }
}
