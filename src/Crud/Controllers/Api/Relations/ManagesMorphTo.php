<?php

namespace Fjord\Crud\Controllers\Api\Relations;

use Fjord\Crud\Fields\Relations\HasOne;
use Fjord\Crud\Fields\Relations\MorphTo;
use Fjord\Crud\Requests\CrudUpdateRequest;

trait ManagesMorphTo
{
    /**
     * Create hasOne relation.
     *
     * @param CrudUpdateRequest $request
     * @param MorphTo $field
     * @param mixed $model
     * @param mixed $relation
     * @return void
     */
    protected function createMorphTo(CrudUpdateRequest $request, MorphTo $field, $model, $relation)
    {
        $morphTo = $field->getRelationQuery($model);

        $model->{$morphTo->getMorphType()} = get_class($relation);
        $model->{$morphTo->getForeignKeyName()} = $relation->id;

        $model->save();
    }

    /**
     * Remove hasOne relation.
     *
     * @param CrudUpdateRequest $request
     * @param MorphTo $field
     * @param mixed $model
     * @param mixed $relation
     * @return void
     */
    protected function destroyMorphTo(CrudUpdateRequest $request, MorphTo $field, $model, $relation)
    {
        $morphTo = $field->getRelationQuery($model);

        $model->{$morphTo->getMorphType()} = '';
        $model->{$morphTo->getForeignKeyName()} = NULL;

        $model->save();
    }
}
