<?php

namespace AwStudio\Fjord\Form;

use Illuminate\Support\Collection;

class FormFieldCollection extends Collection
{
    /**
     * The \AwStudio\Fjord\Form\FormField::class items contained in the collection.
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

    /**
     * Has multiple collection.
     *
     * @return boolean
     */
    public function hasMultipleCollections()
    {
        return $this->groupBy('collection')->keys()->count() > 1;
    }

    /**
     * Has multiple names.
     *
     * @return boolean
     */
    public function hasMultipleNames()
    {
        return $this->groupBy('form_name')->keys()->count() > 1;
    }

    /**
     * Get attribute.
     *
     * @param string $key
     * @param boolean $builder
     * @return void
     */
    public function getAttribute(string $key, $builder = false)
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

    /**
     * Get attribute.
     *
     * @param string $key
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
     * @param array $params
     * @return mixed
     */
    public function __call(string $method, $params = [])
    {
        return $this->getAttribute($method, true);
    }
}
