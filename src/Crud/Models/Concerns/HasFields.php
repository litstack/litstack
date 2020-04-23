<?php

namespace Fjord\Crud\Models\Concerns;

use Fjord\Crud\Models\FormBlock;

trait HasFields
{
    /**
     * Get fields from config.
     *
     * @return Field
     */
    public function getFieldsAttribute()
    {
        return $this->config->form->getRegisteredFields();
    }

    /**
     * Check if field with id exists.
     *
     * @param string $id
     * @return boolean
     */
    public function fieldExists(string $id)
    {
        return $this->findField($id) ? true : false;
    }

    /**
     * Find field by id.
     *
     * @param  string $id
     * @return \Fjord\Crud\Field
     */
    public function findField($id)
    {
        foreach ($this->fields as $field) {
            if ($field->id == $id) {
                return $field;
            }
        }
    }

    /**
     * Get formatted values for the given form_field type.
     *
     * @return void
     */
    public function getFormattedFieldValue($field)
    {
        return $field->cast(
            $this->getFieldValue($field)
        );
    }

    /**
     * Get field value.
     *
     * @param Field $field
     * @return mixed
     */
    public function getFieldValue($field)
    {
        if ($field->isRelation()) {
            return $this->{$field->id};
        }

        if (!is_translatable(static::class)) {
            return $this->{$field->id};
        }

        if ($field->translatable) {
            $value = $this->translation[app()->getLocale()] ?? [];
        } else {
            $value = $this->translation[config('translatable.fallback_locale')] ?? [];
        }

        return $value['value'] ?? null;
    }
}
