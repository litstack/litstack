<?php

namespace AwStudio\Fjord\Form;

use Illuminate\Support\Collection;
use AwStudio\Fjord\Form\Database\FormField;
use AwStudio\Fjord\Support\Facades\FormLoader;

class Form
{
    /**
     * Returns form_field ids from form config.
     *
     * @param  string $path
     * @return array $ids
     */
    protected function getExistingFormFieldIds(string $path)
    {
        $form = FormLoader::load($path, FormField::class);

        return $form
            ->form_fields
            ->pluck('id')
            ->toArray();
    }

    /**
     * Remove form_fields that do not exist in form config.
     *
     * @param  FormFieldCollection $items
     * @param  bool                $loadingCollection
     * @param  bool                $loadingName
     * @return FormFieldCollection $items
     */
    protected function filterByExistingFormFields(FormFieldCollection $items, bool $loadingCollection, bool $loadingName)
    {
        $filteredItems = new FormFieldCollection([]);

        if($loadingName) {

            $ids = $this->getExistingFormFieldIds($items->first()->form_fields_path);
            return $items->whereIn('field_id', $ids);

        } else if(! $loadingCollection) {

            foreach($items->groupBy('collection') as $name => $collectionItems) {
                foreach($collectionItems->groupBy('form_name') as $name => $nameItems) {
                    $ids = $this->getExistingFormFieldIds($nameItems->first()->form_fields_path);
                    $filteredItems = $filteredItems->concat($nameItems->whereIn('field_id', $ids));
                }
            }

        } else {

            foreach($items->groupBy('form_name') as $name => $nameItems) {
                $ids = $this->getExistingFormFieldIds($nameItems->first()->form_fields_path);
                $filteredItems = $filteredItems->concat($nameItems->whereIn('field_id', $ids));
            }

        }

        return $filteredItems;
    }

    /**
     * Load form_field entries from database by collection name and|or for
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
        $loadingName = $name ? true : false;

        $query = Database\FormField::query();

        if($collection) {
            $query->where('collection', $collection);
        }

        if($name) {
            $query->where('form_name', $name);
        }

        $items = new FormFieldCollection($query->get());

        // Remove form_fields from collection that do not exist in config.
        $items = $this->filterByExistingFormFields($items, $loadingCollection, $loadingName);

        $items = $this->getGroups($items, $loadingCollection, $loadingName);

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
        if($loadingCollection && $loadingName) {
            return $items;
        }

        if(! $loadingCollection) {
            $items = new FormFieldCollection($items->groupBy('collection'));

            foreach($items as $collection => $item) {
                $items[$collection] = new FormFieldCollection($item->groupBy('form_name'));
            }

            return $items;
        }

        if(! $loadingName) {
            return new FormFieldCollection($items->groupBy('form_name'));
        }

        return $items;
    }
}
