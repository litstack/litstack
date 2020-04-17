<?php

namespace Fjord\Vue\Crud;

use Fjord\Vue\Component;

class CrudTableComponent extends Component
{
    /**
     * Component attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Add label.
     *
     * @param string $name
     * @return self
     */
    public function label(string $name)
    {
        $this->attributes['label'] = $name;

        return $this;
    }

    /**
     * Reduce table column.
     *
     * @return self
     */
    public function small()
    {
        $this->attributes['reduce'] = true;

        return $this;
    }

    /**
     * Add label.
     *
     * @param string $key
     * @return self
     */
    public function sortBy(string $key)
    {
        $this->attributes['sortBy'] = $key;

        return $this;
    }

    /**
     * Get array.
     *
     * @return array
     */
    public function getArray(): array
    {
        return array_merge([
            'component' => parent::getArray()
        ], $this->attributes);
    }

    /**
     * Get supported methods.
     *
     * @return array
     */
    protected function getSupportedMethods()
    {
        return array_merge(['label', 'sortBy', 'small'], parent::getSupportedMethods());
    }
}
