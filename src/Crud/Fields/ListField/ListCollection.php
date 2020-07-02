<?php

namespace Fjord\Crud\Fields\ListField;

use Illuminate\Database\Eloquent\Collection;

class ListCollection extends Collection
{
    /**
     * Create a new collection.
     *
     * @param mixed $items
     *
     * @return void
     */
    public function __construct($items = [])
    {
        parent::__construct($items);

        $this->setDepth();
    }

    /**
     * Set list items depth.
     *
     * @param self $listItems
     * @param int  $parent_id
     * @param int  $depth
     *
     * @return void
     */
    protected function setDepth(self $listItems = null, $parent_id = 0, $depth = 1)
    {
        if (! $listItems) {
            $listItems = $this;
        }

        foreach ($listItems as $item) {
            if (is_array($item)) {
                $item = clone (object) $item;
            }

            if ($item->parent_id != $parent_id) {
                continue;
            }

            $item->depth = $depth;
            $this->setDepth($listItems, $item->id, $depth + 1);
        }
    }

    /**
     * Flatten list items.
     *
     * @return void
     */
    public function flattenList(self $listItems = null)
    {
        if ($listItems === null) {
            return $this->flattenList($this);
        }

        $flattened = clone $listItems;

        foreach ($listItems as $listItem) {
            if (! $listItem->children instanceof self) {
                continue;
            }

            if ($listItem->children->isEmpty()) {
                continue;
            }

            $flattened = $flattened->merge($this->flattenList($listItem->children));
        }

        return $flattened;
    }

    /**
     * Unflatten list items.
     *
     * @param Collection $listItems
     * @param int        $parent_id
     * @param int        $depth
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function unflattenList(self $listItems = null, $parent_id = 0, $depth = 1)
    {
        if ($listItems === null) {
            return $this->unflattenList($this);
        }

        $unflattened = $listItems->where('parent_id', $parent_id);

        foreach ($unflattened as $item) {
            $item->setAttribute('depth', $depth);
            $item->setAttribute('children', $this->unflattenList($listItems, $item->id, $depth + 1));
        }

        return $unflattened;
    }
}
