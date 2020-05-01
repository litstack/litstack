<?php

namespace Fjord\Crud;

class ManyRelationField extends Field
{
    use Concerns\ManagesRelationField;

    /**
     * Field Vue component.
     *
     * @var string
     */
    protected $component = 'fj-field-relation';

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
