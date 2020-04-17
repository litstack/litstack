<?php

namespace Fjord\Crud;

class ManyRelationField extends Field
{
    use Concerns\ManagesRelationField;

    /**
     * Resolve query for many relations.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function resolveQuery($query)
    {
        return $query->get();
    }
}
