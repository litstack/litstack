<?php

namespace Fjord\Crud\Models\Relations;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Collection;

class ListRelation extends MorphMany
{
    /**
     * Get results.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getResults()
    {
        return $this->unflatten(
            parent::getResults()
        );
    }

    /**
     * Get flat list.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFlat()
    {
        return parent::getResults();
    }

    /**
     * Unflatten results.
     *
     * @param Collection $listItems
     * @param integer $parent_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function unflatten(Collection $listItems, $parent_id = 0)
    {
        $unflattened = $listItems->where('parent_id', $parent_id);

        foreach ($unflattened as $item) {
            $item->setAttribute('children', $this->unflatten($listItems, $item->id));
        }

        return $unflattened;
    }
}
