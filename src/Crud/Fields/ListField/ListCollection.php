<?php

namespace Ignite\Crud\Fields\ListField;

use Ignite\Crud\Models\ListItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ListCollection extends Collection
{
    /**
     * Create a new collection.
     *
     * @param  mixed $items
     * @return void
     */
    public function __construct($items = [], $setDepth = false)
    {
        parent::__construct($items);

        if ($setDepth) {
            $this->setDepth();
        }
    }

    /**
     * Set list items depth.
     *
     * @return void
     */
    public function setDepth()
    {
        foreach ($this->items as $key => $item) {
            // In some cases $item is not a Model instance but an array.
            // Converting $item to an object in that case.
            if (is_array($item)) {
                $this->items[$key]['depth'] = $this->getDepth((object) $item);
            } else {
                $this->items[$key]->depth = $this->getDepth($item);
            }
        }
    }

    /**
     * Get depth for item.
     *
     * @param  array|ListItem $item
     * @return int
     */
    protected function getDepth($item)
    {
        $depth = 1;

        $parent_id = $item->parent_id ?? 0;

        if ($parent = collect($this->items)->where('id', $parent_id)->first()) {
            $depth += $this->getDepth($parent);
        }

        return $depth;
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
            $item->setRelation('children', $this->unflattenList($listItems, $item->id, $depth + 1));
        }

        return $unflattened;
    }
}
