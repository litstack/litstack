<?php

namespace Fjord\Crud\Fields\Traits;

trait TranslatableField
{
    /**
     * Set field translatable.
     *
     * @param bool $translatable
     *
     * @return self
     */
    public function translatable(bool $translatable = true)
    {
        $this->setAttribute('translatable', $translatable);

        return $this;
    }

    /**
     * Set translatable default attribute.
     *
     * @return bool
     */
    public function setTranslatableDefault(): bool
    {
        if (! class_exists($this->model)) {
            return false;
        }

        return is_attribute_translatable($this->id, $this->model);
    }
}
