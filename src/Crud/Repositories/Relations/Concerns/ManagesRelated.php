<?php

namespace Ignite\Crud\Repositories\Relations\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait ManagesRelated
{
    /**
     * Get related model from request.
     *
     * @param  Request $request
     * @return mixed
     */
    protected function getRelated(Request $request, $model)
    {
        return $this->field->getQuery()->findOrFail($request->related_id);
    }

    /**
     * Get related model from request and delete if it should be deleted.
     *
     * @param  Request $request
     * @return mixed
     */
    public function getRelatedOrDelete(Request $request, $model)
    {
        return $this->deleteIfDesired(
            $request,
            $this->getRelated($request, $model)
        );
    }

    /**
     * Delete the related model if.
     *
     * @param  Request    $request
     * @param  Model      $model
     * @return void|Model
     */
    protected function deleteIfDesired(Request $request, Model $related)
    {
        if (! $request->delete_unlinked) {
            return $related;
        }

        $related->delete();
    }

    /**
     * Check if max items are reached.
     *
     * @param  mixed $model
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function checkMaxItems($model)
    {
        if (! $this->field->maxItems) {
            return;
        }

        $relationsCount = $this->field->getRelationQuery($model)->count();

        if ($relationsCount < $this->field->maxItems) {
            return;
        }

        abort(405, __lit('crud.fields.relation.messages.max_items_reached', [
            'count' => $this->field->maxItems,
        ]));
    }
}
