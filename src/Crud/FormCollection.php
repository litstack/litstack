<?php

namespace Ignite\Crud;

use Illuminate\Support\Collection;

class FormCollection extends Collection
{
    /**
     * The Form items contained in the collection.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Get collection groups in this FormCollection.
     *
     * @return array
     */
    public function getCollections()
    {
        return $this->groupBy('collection')->keys()->toArray();
    }

    /**
     * Get form_name groups in this FormCollection.
     *
     * @return array
     */
    public function getNames()
    {
        return $this->groupBy('form_name')->keys()->toArray();
    }

    /**
     * Has multiple collection.
     *
     * @return bool
     */
    public function hasMultipleCollections()
    {
        return $this->groupBy('collection')->keys()->count() > 1;
    }

    /**
     * Has multiple names.
     *
     * @return bool
     */
    public function hasMultipleNames()
    {
        return $this->groupBy('form_name')->keys()->count() > 1;
    }

    /**
     * Get attribute.
     *
     * @param string $key
     * @param bool   $query
     *
     * @return void
     */
    public function getAttribute(string $key, $query = false)
    {
        // Return values for array key if items is not a list.
        // This returns Form models
        if (array_key_exists($key, $this->items)) {
            return $this->items[$key];
        }

        $form = $this->where('field_id', $key)->first();

        if (! $form) {
            return;
        }

        if ($query) {
            return $form->$key();
        }

        return $form->getFormattedFieldValue($form->field);
    }

    /**
     * Get attribute.
     *
     * @param string $key
     *
     * @return void
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Get query builder.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->getAttribute($method, $query = true);
    }
}
