<?php

namespace Fjord\Crud;

use Fjord\Crud\Fields\Relations\ManyRelation;
use Fjord\Exceptions\InvalidArgumentException;

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
        return $query->getResults();
    }

    /**
     * Set sortable field.
     *
     * @return self
     */
    public function sortable($sort = true)
    {
        if ($this instanceof ManyRelation) {
            if (!$this->related) {
                throw new InvalidArgumentException('You may set a related Model before making the Field sortable.', [
                    'function' => 'sortable',
                    'class' => 'Fjord\Crud\ManyRelationField'
                ]);
            }
        }

        if (empty($this->relation->getQuery()->getQuery()->orders)) {
            throw new InvalidArgumentException('You may add orderBy to the related query for ' . $this->id . ' in ' . $this->model . '.', [
                'function' => 'sortable',
                'class' => 'Fjord\Crud\ManyRelationField'
            ]);
        }

        $this->attributes['sortable'] = $sort;

        return $this;
    }
}
