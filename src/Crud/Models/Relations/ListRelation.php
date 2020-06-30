<?php

namespace Fjord\Crud\Models\Relations;

use Illuminate\Database\Eloquent\Collection;
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
        // Flattening unflattened list to have "depth" attribute.
        return $this->flatten(
            $this->getResults()
        );
    }

    /**
     * Flatten results.
     *
     * @param Collection $listItems
     * @param int        $parent_id
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function flatten($listItems)
    {
        foreach ($listItems as $listItem) {
            if (empty($listItem->children)) {
                continue;
            }

            return $listItems->merge($this->flatten($listItem->children));
        }

        return $listItems;
    }

    /**
     * Unflatten results.
     *
     * @param Collection $listItems
     * @param int        $parent_id
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function unflatten(Collection $listItems, $parent_id = 0, $depth = 1)
    {
        $unflattened = $listItems->where('parent_id', $parent_id);

        foreach ($unflattened as $item) {
            $item->setAttribute('depth', $depth);
            $item->setAttribute('children', $this->unflatten($listItems, $item->id, ++$depth));
        }

        return $unflattened;
    }
}
