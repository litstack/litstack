<?php

namespace Fjord\Crud;

use Fjord\Crud\Models\FormField;
use Illuminate\Support\Collection;

class FormFieldLoader
{
    /**
     * Remove FormField's that do not exist in form config.
     *
     * @param  FormFieldCollection $items
     * @param  bool                $loadingCollection
     * @param  bool                $loadingName
     * @return FormFieldCollection $items
     */
    protected function filterByExistingFields(FormFieldCollection $items, bool $loadingCollection, bool $loadingForm)
    {
        $filteredItems = new FormFieldCollection([]);

        foreach ($items as $item) {
            if ($item->field) {
                $filteredItems->push($item);
            }
        }

        return $filteredItems;
    }

    /**
     * Load FormField entries from database by collection name and|or for
     * form_name. If the collection name or the form_name was not set a group is
     * returned where the respective collection or form can be called with the
     * name.
     *
     * @param  string $collection
     * @param  string $name
     * @return FormFieldCollection
     */
    public function load(string $collection = null, string $name = null)
    {
        $loadingCollection = $collection ? true : false;
        $loadingForm = $name ? true : false;

        $query = FormField::query();

        if ($collection) {
            $query->where('collection', $collection);
        }

        if ($name) {
            $query->where('form_name', $name);
        }

        $items = new FormFieldCollection($query->get());

        // Remove FormField's from collection that do not exist in config.
        $items = $this->filterByExistingFields($items, $loadingCollection, $loadingForm);

        $items = $this->getGroups($items, $loadingCollection, $loadingForm);

        return $items;
    }

    /**
     * Get groups for collection and form_name and
     * create nested Collection based on the groups.
     *
     * @param  Illuminate\Support\Collection $items Collection of AwStudio\Fjord\Form\Database\FormField
     * @param  bool $loading_collection
     * @param  bool $loading_name
     *
     * @return AwStudio\Fjord\Form\FormFieldCollection|AwStudio\Fjord\Form\Collection
     */
    protected function getGroups(Collection $items, bool $loadingCollection, bool $loadingName)
    {
        if ($loadingCollection && $loadingName) {
            return $items;
        }

        if (!$loadingCollection) {
            $items = new FormFieldCollection($items->groupBy('collection'));

            foreach ($items as $collection => $item) {
                $items[$collection] = new FormFieldCollection($item->groupBy('form_name'));
            }

            return $items;
        }

        if (!$loadingName) {
            return new FormFieldCollection($items->groupBy('form_name'));
        }

        return $items;
    }
}
