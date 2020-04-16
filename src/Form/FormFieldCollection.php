<?php

namespace Fjord\Form;

use Illuminate\Support\Collection;

class FormFieldCollection extends Collection
{
    /**
     * The \Fjord\Form\FormField::class items contained in the collection.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Get collection groups in this FormFieldCollection.
     *
     * @return array
     */
    public function getCollections()
    {
        return $this->groupBy('collection')->keys()->toArray();
    }

    /**
     * Get form_name groups in this FormFieldCollection.
     *
     * @return array
     */
    public function getNames()
    {
        return $this->groupBy('form_name')->keys()->toArray();
    }

    public function hasMultipleCollections()
    {
        return $this->groupBy('collection')->keys()->count() > 1;
    }

    public function hasMultipleNames()
    {
        return $this->groupBy('form_name')->keys()->count() > 1;
    }

    public function getAttribute($key, $builder = false)
    {
        // Return values for array key if items is not a list.
        // This returns FormFields models
        if (array_key_exists($key, $this->items)) {
            return $this->items[$key];
        }

        $form_field = $this->where('field_id', $key)->first();

        if (!$form_field) {
            return;
        }

        return $form_field->getFormattedFormFieldValue($form_field->form_field, $builder);
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function __call($method, $attributes)
    {
        return $this->getAttribute($method, true);
    }
}
