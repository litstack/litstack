<?php

namespace Fjord\Crud\Fields\Concerns;

trait TranslatableField
{
    /**
     * Available field attributes.
     *
     * @var array
     */
    public $availableTranslatableAttributes = [
        'translatable'
    ];

    /**
     * Set translatable default attribute
     *
     * @return boolean
     */
    public function setTranslatableAttribute(): bool
    {
        return is_attribute_translatable($this->id, $this->model);
    }
}
