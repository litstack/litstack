<?php

namespace Lit\Crud\Fields\Traits;

use Lit\Crud\Models\LitFormModel;
use Lit\Support\Facades\Lit;

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

        if (new $this->model instanceof LitFormModel) {
            return Lit::isAppTranslatable();
        }

        return is_attribute_translatable($this->id, $this->model);
    }
}
