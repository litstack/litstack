<?php

namespace Fjord\Crud;

class ManyRelationField extends Field
{
    use Concerns\ManagesRelationField;

    /**
     * Resolve query from 
     *
     * @return void
     */
    public function resolveQuery($query)
    {
        return $query->get();
    }
}
