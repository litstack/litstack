<?php

namespace Ignite\Crud\Fields\Traits;

use Ignite\Crud\Models\LitFormModel;
use Ignite\Support\Facades\Lit;

trait TranslatableField
{
    /**
     * Set field translatable.
     *
     * @param  bool $translatable
     * @return self
     */
    public function translatable(bool $translatable = true)
    {
        $this->setAttribute('translatable', $translatable);

        return $this;
    }

    public function setModel($model)
    {
        parent::setModel($model);

        $this->setAttribute(
            'translatable', $this->setTranslatableDefault()
        );
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
