<?php

namespace Fjord\Crud\Fields\Traits;

use Fjord\Crud\Models\FjordFormModel;
use Fjord\Support\Facades\Fjord;

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
        
        if (new $this->model instanceof FjordFormModel) {
            return Fjord::isAppTranslatable();
        }

        return is_attribute_translatable($this->id, $this->model);
    }
}
