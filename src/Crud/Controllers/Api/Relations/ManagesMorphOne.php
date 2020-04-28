<?php

namespace Fjord\Crud\Controllers\Api\Relations;

use Fjord\Crud\Fields\Relations\MorphOne;
use Fjord\Crud\Requests\CrudUpdateRequest;

trait ManagesMorphOne
{
    /**
     * Create morphOne relation.
     *
     * @param CrudUpdateRequest $request
     * @param MorphOne $field
     * @param mixed $model
     * @param mixed $relation
     * @return void
     */
    protected function createMorphOne(CrudUpdateRequest $request, MorphOne $field, $model, $relation)
    {
        $morphOne = $field->relation($model, $query = true);

        $query = [
            $morphOne->getMorphType() => get_class($model),
            $morphOne->getForeignKeyName() => $model->{$morphOne->getLocalKeyName()}
        ];

        // Remove existsing morphOne relations.
        $morphOne->where($query)->update([
            $morphOne->getMorphType() => '',
            $morphOne->getForeignKeyName() => 0
        ]);

        // Create new relation.
        $relation->update($query);
    }

    /**
     * Remove morphOne relation.
     *
     * @param CrudUpdateRequest $request
     * @param MorphOne $field
     * @param mixed $model
     * @param mixed $relation
     * @return void
     */
    protected function destroyMorphOne(CrudUpdateRequest $request, MorphOne $field, $model, $relation)
    {
        $morphOne = $field->relation($model, $query = true);

        $relation->{$morphOne->getMorphType()} = '';
        $relation->{$morphOne->getForeignKeyName()} = 0;
        $relation->update();
    }
}
