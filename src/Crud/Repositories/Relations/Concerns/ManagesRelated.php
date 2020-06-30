<?php

namespace Fjord\Crud\Repositories\Relations\Concerns;

use Illuminate\Http\Request;

trait ManagesRelated
{
    /**
     * Get related model from request.
     *
     * @param Request $request
     * @return mixed
     */
    public function getRelated(Request $request, $model)
    {
        return $this->field->getQuery()->findOrFail($request->related_id);
    }
}
