<?php

namespace Fjord\Crud;

class OneRelationField extends Field
{
    use Concerns\ManagesRelationField;

    /**
     * Resolve query for one relations.
     *
     * @return mixed
     */
    public function resolveQuery($query)
    {
        return $query->first();
    }
}
