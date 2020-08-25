<?php

namespace Ignite\Crud\Fields\ListField;

use Illuminate\Database\Eloquent\Relations\MorphMany;

class ListRelation extends MorphMany
{
    /**
     * Get results.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getResults()
    {
        return parent::getResults()
            ->sortBy('order_column')
            ->unflattenList();
    }

    /**
     * Get flat list.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFlat()
    {
        // Flattening unflattened list to have "depth" attribute.
        return $this->getResults()->flattenList();
    }
}
