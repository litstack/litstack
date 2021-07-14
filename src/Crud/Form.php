<?php

namespace Ignite\Crud;

use Ignite\Crud\Models\Form as FormModel;
use Illuminate\Cache\CacheManager;
use Illuminate\Support\Collection;

class Form
{
    /**
     * Registered fields.
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Cache manager instance.
     *
     * @var CacheManager
     */
    protected $cache;

    /**
     * Create new Form instance.
     *
     * @param  CacheManager $cache
     * @return void
     */
    public function __construct(CacheManager $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Load FormField entries from database by collection name and|or for
     * form_name. If the collection name or the form_name was not set a group is
     * returned where the respective collection or form can be called with the
     * name.
     *
     * @param  string                   $collection
     * @param  string                   $name
     * @return FormCollection|FormModel
     */
    public function load(string $collection = null, string $name = null)
    {
        return $this->cache->remember(
            $this->getCacheKey($collection, $name),
            config('lit.crud.form_ttl'),
            function () use ($collection, $name) {
                $loadingCollection = $collection ? true : false;
                $loadingForm = $name ? true : false;

                $query = FormModel::query();

                if ($collection) {
                    $query->where('collection', $collection);
                }

                if ($name) {
                    $query->where('form_name', $name);
                }

                $items = new FormCollection($query->get());

                return $this->getGroups($items, $loadingCollection, $loadingForm);
            });
    }

    /**
     * Get groups for collection and form_name and
     * create nested Collection based on the groups.
     *
     * @param  Illuminate\Support\Collection $items
     * @param  bool                          $loading_collection
     * @param  bool                          $loading_name
     * @return FormCollection
     */
    protected function getGroups(Collection $items, bool $loadingCollection, bool $loadingName)
    {
        if ($loadingCollection && $loadingName) {
            return $items->first();
        }

        if (! $loadingCollection) {
            return $this->getCollectionGroups($items);
        }

        if (! $loadingName) {
            return $this->getFormGroups($items);
        }

        return $items;
    }

    /**
     * Get cache key.
     *
     * @param  string  $collection
     * @param  string  $name
     * @return void
     */
    public function getCacheKey(string $collection = null, string $name = null)
    {
        $key = 'lit.form';

        if (is_null($collection)) {
            return $key;
        }

        $key .= ".{$collection}";

        if (is_null($collection)) {
            return $key;
        }

        return $key.".{$name}";
    }

    /**
     * Get collection groups.
     *
     * @param  Collection     $items
     * @return FormCollection
     */
    protected function getCollectionGroups(Collection $items)
    {
        $items = new FormCollection($items->groupBy('collection'));

        foreach ($items as $collection => $item) {
            $items[$collection] = new FormCollection(
                $this->getFormGroups($item)
            );
        }

        return $items;
    }

    /**
     * Get form groups.
     *
     * @param  Collection     $items
     * @return FormCollection
     */
    protected function getFormGroups(Collection $items)
    {
        $items = new FormCollection($items->groupBy('form_name'));

        foreach ($items as $collection => $item) {
            $items[$collection] = $item->first();
        }

        return $items;
    }

    /**
     * Add field.
     *
     * @param  string $alias
     * @param  string $field
     * @return void
     */
    public function field($alias, $field)
    {
        $this->fields[$alias] = $field;
    }

    /**
     * Register field.
     *
     * @param  string $alias
     * @param  string $field
     * @return void
     *
     * @deprecated
     */
    public function registerField($alias, $field)
    {
        $this->fields[$alias] = $field;
    }

    /**
     * Determine if field alias exists.
     *
     * @param  mixed $field
     * @return bool
     */
    public function fieldExists(string $alias)
    {
        return array_key_exists($alias, $this->fields);
    }

    /**
     * Get field by alias.
     *
     * @param  string $alias
     * @return string
     */
    public function getFieldClass(string $alias)
    {
        return $this->fields[$alias] ?? null;
    }

    /**
     * Get fields.
     *
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Get field class for the given alias.
     *
     * @param  string $alias
     * @return string
     */
    public function getField(string $alias)
    {
        return $this->fields[$alias] ?? null;
    }
}
