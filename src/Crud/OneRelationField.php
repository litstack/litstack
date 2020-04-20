<?php

namespace Fjord\Crud;

class OneRelationField extends Field
{
    use Concerns\ManagesRelationField;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-form-relation-one';

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
